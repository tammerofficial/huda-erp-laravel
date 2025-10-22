<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\EmployeeWorkLog;
use App\Models\ProductionStage;
use App\Models\AttendanceRecord;
use App\Models\ProductionLog;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Calculate monthly payroll for an employee using real data
     */
    public function calculateMonthlyPayroll(Employee $employee, int $month, int $year): array
    {
        $periodStart = Carbon::create($year, $month, 1)->startOfMonth();
        $periodEnd = Carbon::create($year, $month, 1)->endOfMonth();

        // Get attendance data
        $attendance = AttendanceRecord::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $totalHours = $attendance->sum('hours_worked');
        $overtimeHours = $attendance->sum('overtime_hours');
        $daysPresent = $attendance->where('status', 'present')->count();

        // Get production data
        $productionLogs = ProductionLog::where('employee_id', $employee->id)
            ->whereMonth('start_time', $month)
            ->whereYear('start_time', $year)
            ->where('quality_status', 'approved')
            ->get();

        $productionEarnings = $productionLogs->sum('earnings');
        $totalPieces = $productionLogs->sum('pieces_completed');

        // Calculate base salary based on salary type
        $baseSalary = $this->calculateBaseSalary($employee, $attendance, $productionEarnings);
        
        // Calculate overtime
        $overtimeAmount = $this->calculateOvertimeAmount($employee, $overtimeHours);
        
        // Calculate bonuses and deductions
        $bonuses = $this->calculateBonuses($employee, $productionLogs);
        $deductions = $this->calculateDeductions($employee, $attendance);

        return [
            'base_salary' => $baseSalary,
            'overtime_hours' => $overtimeHours,
            'overtime_amount' => $overtimeAmount,
            'bonuses' => $bonuses,
            'deductions' => $deductions,
            'total_amount' => $baseSalary + $overtimeAmount + $bonuses - $deductions,
            'metadata' => [
                'days_present' => $daysPresent,
                'total_hours' => $totalHours,
                'pieces_completed' => $totalPieces,
                'efficiency_avg' => $productionLogs->avg('efficiency_rate'),
            ]
        ];
    }

    /**
     * Calculate base salary based on salary type and real data
     */
    protected function calculateBaseSalary(Employee $employee, $attendance, $productionEarnings): float
    {
        $salaryType = $employee->salary_type ?? 'monthly';
        
        if ($salaryType === 'monthly') {
            $workingDays = Setting::get('working_days_per_month', 26);
            $daysPresent = $attendance->where('status', 'present')->count();
            return $employee->salary * ($daysPresent / $workingDays);
        } 
        elseif ($salaryType === 'hourly') {
            $totalHours = $attendance->sum('hours_worked');
            return $totalHours * ($employee->rate_per_hour ?? 0);
        } 
        elseif ($salaryType === 'per_piece') {
            return $productionEarnings;
        }
        
        return 0;
    }

    /**
     * Calculate overtime amount
     */
    protected function calculateOvertimeAmount(Employee $employee, $overtimeHours): float
    {
        if ($overtimeHours <= 0) return 0;
        
        $overtimeRate = Setting::get('overtime_multiplier', 1.5);
        
        if ($employee->salary_type === 'monthly') {
            $hourlyRate = $employee->salary / (26 * 8);
            return $overtimeHours * $hourlyRate * $overtimeRate;
        } 
        elseif ($employee->salary_type === 'hourly') {
            return $overtimeHours * ($employee->rate_per_hour ?? 0) * $overtimeRate;
        }
        
        return 0;
    }

    /**
     * Calculate bonuses based on performance
     */
    protected function calculateBonuses(Employee $employee, $productionLogs): float
    {
        $bonus = 0;
        
        // Efficiency bonus
        $avgEfficiency = $productionLogs->avg('efficiency_rate');
        if ($avgEfficiency > 120) {
            $bonus += 50; // 50 KWD
        } elseif ($avgEfficiency > 100) {
            $bonus += 25;
        }
        
        // Production bonus
        $totalPieces = $productionLogs->sum('pieces_completed');
        if ($totalPieces > 500) {
            $bonus += 100;
        } elseif ($totalPieces > 300) {
            $bonus += 50;
        }
        
        return $bonus;
    }

    /**
     * Calculate deductions
     */
    protected function calculateDeductions(Employee $employee, $attendance): float
    {
        $deduction = 0;
        
        // Absence deduction
        $absences = $attendance->where('status', 'absent')->count();
        $dailyRate = $employee->salary / 26;
        $deduction += $absences * $dailyRate;
        
        // Late deduction
        $lateCount = $attendance->where('status', 'late')->count();
        if ($lateCount > 3) {
            $deduction += ($lateCount - 3) * 5; // 5 KWD per late after 3rd
        }
        
        return $deduction;
    }

    /**
     * Calculate overtime payment
     */
    public function calculateOvertimePayment(Employee $employee, float $hours): float
    {
        if (!$employee->salary || $hours <= 0) {
            return 0;
        }

        $workingDaysPerMonth = (float) Setting::where('category', 'payroll')
            ->where('key', 'working_days_per_month')
            ->value('value') ?? 26;

        $workingHoursPerDay = (float) Setting::where('category', 'payroll')
            ->where('key', 'working_hours_per_day')
            ->value('value') ?? 8;

        $overtimeMultiplier = (float) Setting::where('category', 'payroll')
            ->where('key', 'overtime_multiplier')
            ->value('value') ?? 1.5;

        $hourlyRate = $employee->salary / ($workingDaysPerMonth * $workingHoursPerDay);

        return $hourlyRate * $hours * $overtimeMultiplier;
    }

    /**
     * Get total production work hours from production stages
     */
    public function getProductionWorkHours(Employee $employee, Carbon $start, Carbon $end): float
    {
        $minutes = ProductionStage::where('employee_id', $employee->id)
            ->where('status', 'completed')
            ->whereBetween('start_time', [$start, $end])
            ->sum('duration_minutes');

        return $minutes / 60; // Convert to hours
    }

    /**
     * Get total work hours from work logs
     */
    public function getWorkLogHours(Employee $employee, Carbon $start, Carbon $end): float
    {
        return EmployeeWorkLog::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->whereNotNull('approved_at')
            ->sum('hours_worked');
    }

    /**
     * Get overtime hours
     */
    protected function getOvertimeHours(Employee $employee, Carbon $start, Carbon $end): float
    {
        return EmployeeWorkLog::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->where('work_type', 'overtime')
            ->whereNotNull('approved_at')
            ->sum('hours_worked');
    }

    /**
     * Calculate production bonus based on completed stages
     */
    public function calculateProductionBonus(Employee $employee, Carbon $start, Carbon $end): float
    {
        // Simple bonus: count completed production stages
        $completedStages = ProductionStage::where('employee_id', $employee->id)
            ->where('status', 'completed')
            ->whereBetween('end_time', [$start, $end])
            ->count();

        // Bonus per completed stage (can be configured in settings)
        $bonusPerStage = 5; // 5 KWD per stage

        return $completedStages * $bonusPerStage;
    }

    /**
     * Generate payroll record
     */
    public function generatePayroll(Employee $employee, int $month, int $year, ?int $createdBy = null): Payroll
    {
        $periodStart = Carbon::create($year, $month, 1)->startOfMonth();
        $periodEnd = Carbon::create($year, $month, 1)->endOfMonth();

        // Check if payroll already exists
        $existing = Payroll::where('employee_id', $employee->id)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->first();

        if ($existing) {
            throw new \Exception("Payroll for {$employee->user->name} for this period already exists.");
        }

        $calculation = $this->calculateMonthlyPayroll($employee, $month, $year);

        return Payroll::create([
            'employee_id' => $employee->id,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'base_salary' => $calculation['base_salary'],
            'overtime_hours' => $calculation['overtime_hours'],
            'overtime_amount' => $calculation['overtime_amount'],
            'bonuses' => $calculation['bonuses'],
            'deductions' => $calculation['deductions'],
            'total_amount' => $calculation['total_amount'],
            'status' => 'draft',
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Approve payroll
     */
    public function approvePayroll(Payroll $payroll): Payroll
    {
        $payroll->update(['status' => 'approved']);
        return $payroll;
    }

    /**
     * Process payment - mark as paid and create accounting entry
     */
    public function processPayment(Payroll $payroll, string $paymentMethod = 'bank_transfer'): Payroll
    {
        DB::transaction(function () use ($payroll, $paymentMethod) {
            $payroll->update([
                'status' => 'paid',
                'payment_date' => now(),
                'payment_method' => $paymentMethod,
            ]);

            // Create accounting entry
            app(AccountingService::class)->recordPayrollExpense($payroll);
        });

        return $payroll->fresh();
    }

    /**
     * Generate bulk payrolls for all active employees
     */
    public function generateBulkPayrolls(int $month, int $year, ?int $createdBy = null): array
    {
        $employees = Employee::where('employment_status', 'active')
            ->whereNotNull('salary')
            ->get();

        $generated = [];
        $errors = [];

        foreach ($employees as $employee) {
            try {
                $payroll = $this->generatePayroll($employee, $month, $year, $createdBy);
                $generated[] = $payroll;
            } catch (\Exception $e) {
                $errors[] = [
                    'employee' => $employee->user->name ?? "Employee #{$employee->id}",
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'generated' => $generated,
            'errors' => $errors,
        ];
    }
}

