<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\EmployeeWorkLog;
use App\Models\ProductionStage;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Calculate monthly payroll for an employee
     */
    public function calculateMonthlyPayroll(Employee $employee, int $month, int $year): array
    {
        $periodStart = Carbon::create($year, $month, 1)->startOfMonth();
        $periodEnd = Carbon::create($year, $month, 1)->endOfMonth();

        $baseSalary = $this->getBasePayForPeriod($employee, $periodStart, $periodEnd);
        $overtimeHours = $this->getOvertimeHours($employee, $periodStart, $periodEnd);
        $overtimeAmount = $this->calculateOvertimePayment($employee, $overtimeHours);
        $bonuses = $this->calculateProductionBonus($employee, $periodStart, $periodEnd);

        return [
            'base_salary' => $baseSalary,
            'overtime_hours' => $overtimeHours,
            'overtime_amount' => $overtimeAmount,
            'bonuses' => $bonuses,
            'deductions' => 0, // Can be added manually
            'total_amount' => $baseSalary + $overtimeAmount + $bonuses,
        ];
    }

    /**
     * Get base pay for a period (pro-rated if needed)
     */
    public function getBasePayForPeriod(Employee $employee, Carbon $start, Carbon $end): float
    {
        if (!$employee->salary) {
            return 0;
        }

        // Check if employee was hired during this period
        if ($employee->hire_date && Carbon::parse($employee->hire_date)->gt($start)) {
            $hireDate = Carbon::parse($employee->hire_date);
            $daysInMonth = $start->daysInMonth;
            $workedDays = $hireDate->diffInDays($end->copy()->addDay());
            return ($employee->salary / $daysInMonth) * $workedDays;
        }

        return (float) $employee->salary;
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

