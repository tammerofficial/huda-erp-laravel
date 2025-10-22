<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\EmployeeWorkLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee.user');

        // Filters
        if ($request->filled('month')) {
            $date = Carbon::parse($request->month);
            $query->whereYear('period_start', $date->year)
                  ->whereMonth('period_start', $date->month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date_from')) {
            $query->where('period_start', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('period_end', '<=', $request->date_to);
        }

        $payrolls = $query->orderBy('period_start', 'desc')->paginate(20);
        $employees = Employee::with('user')->get();

        // Statistics
        $stats = [
            'total_paid' => Payroll::where('status', 'paid')->sum('total_amount'),
            'total_pending' => Payroll::where('status', 'draft')->sum('total_amount'),
            'total_approved' => Payroll::where('status', 'approved')->sum('total_amount'),
            'employee_count' => Employee::count(),
        ];

        // Monthly breakdown
        $monthlyData = Payroll::select(
            DB::raw('DATE_FORMAT(period_start, "%Y-%m") as month'),
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'paid')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->limit(12)
        ->get();

        return view('payroll.index', compact('payrolls', 'employees', 'stats', 'monthlyData'));
    }

    public function create()
    {
        $employees = Employee::with('user')->where('employment_status', 'active')->get();
        return view('payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_amount' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['total_amount'] = $validated['base_salary'] 
            + ($validated['overtime_amount'] ?? 0) 
            + ($validated['bonuses'] ?? 0) 
            - ($validated['deductions'] ?? 0);
        
        $validated['status'] = 'draft';
        $validated['created_by'] = auth()->id();

        $payroll = Payroll::create($validated);

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'تم إنشاء راتب الموظف بنجاح');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.user', 'createdBy');
        
        // Get work logs for the period
        $workLogs = EmployeeWorkLog::where('employee_id', $payroll->employee_id)
            ->whereBetween('check_in', [$payroll->period_start, $payroll->period_end])
            ->orderBy('check_in', 'desc')
            ->get();

        return view('payroll.show', compact('payroll', 'workLogs'));
    }

    public function edit(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->route('payroll.show', $payroll)
                ->with('error', 'لا يمكن تعديل راتب تم دفعه');
        }

        $employees = Employee::with('user')->where('employment_status', 'active')->get();
        return view('payroll.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->route('payroll.show', $payroll)
                ->with('error', 'لا يمكن تعديل راتب تم دفعه');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_amount' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['total_amount'] = $validated['base_salary'] 
            + ($validated['overtime_amount'] ?? 0) 
            + ($validated['bonuses'] ?? 0) 
            - ($validated['deductions'] ?? 0);

        $payroll->update($validated);

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'تم تحديث راتب الموظف بنجاح');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->route('payroll.index')
                ->with('error', 'لا يمكن حذف راتب تم دفعه');
        }

        $payroll->delete();

        return redirect()->route('payroll.index')
            ->with('success', 'تم حذف راتب الموظف بنجاح');
    }

    public function generateMonthly(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $month = Carbon::parse($validated['month']);
        $periodStart = $month->copy()->startOfMonth();
        $periodEnd = $month->copy()->endOfMonth();

        // Get employees
        $employeesQuery = Employee::where('employment_status', 'active');
        if (!empty($validated['employee_ids'])) {
            $employeesQuery->whereIn('id', $validated['employee_ids']);
        }
        $employees = $employeesQuery->get();

        $created = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            // Check if payroll already exists
            $exists = Payroll::where('employee_id', $employee->id)
                ->where('period_start', $periodStart)
                ->where('period_end', $periodEnd)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Calculate overtime
            $workLogs = EmployeeWorkLog::where('employee_id', $employee->id)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->get();

            $totalHours = 0;
            $overtimeHours = 0;
            $standardHoursPerDay = 8;

            foreach ($workLogs as $log) {
                if ($log->hours_worked) {
                    $totalHours += $log->hours_worked;
                    if ($log->hours_worked > $standardHoursPerDay) {
                        $overtimeHours += ($log->hours_worked - $standardHoursPerDay);
                    }
                }
            }

            $overtimeAmount = $overtimeHours * ($employee->overtime_rate ?? 0);

            Payroll::create([
                'employee_id' => $employee->id,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'base_salary' => $employee->salary,
                'overtime_hours' => $overtimeHours,
                'overtime_amount' => $overtimeAmount,
                'bonuses' => 0,
                'deductions' => 0,
                'total_amount' => $employee->salary + $overtimeAmount,
                'status' => 'draft',
                'payment_method' => $employee->payment_method ?? 'bank_transfer',
                'created_by' => auth()->id(),
            ]);

            $created++;
        }

        return redirect()->route('payroll.index', ['month' => $month->format('Y-m')])
            ->with('success', "تم إنشاء {$created} راتب. تم تخطي {$skipped} راتب (موجود مسبقاً)");
    }

    public function showGenerateForm()
    {
        $employees = Employee::with('user')->where('employment_status', 'active')->get();
        return view('payroll.generate', compact('employees'));
    }

    public function approve(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return redirect()->route('payroll.show', $payroll)
                ->with('error', 'لا يمكن الموافقة على هذا الراتب');
        }

        $payroll->update(['status' => 'approved']);

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'تم الموافقة على الراتب بنجاح');
    }

    public function markAsPaid(Payroll $payroll, Request $request)
    {
        if ($payroll->status === 'paid') {
            return redirect()->route('payroll.show', $payroll)
                ->with('error', 'الراتب مدفوع بالفعل');
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $payroll->update([
            'status' => 'paid',
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
        ]);

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'تم تحديد الراتب كمدفوع بنجاح');
    }
}

