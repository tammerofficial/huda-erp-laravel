<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendanceRecords = AttendanceRecord::with('employee')
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('attendance.index', compact('attendanceRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('employment_status', 'active')->get();
        return view('attendance.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day',
            'notes' => 'nullable|string',
        ]);

        AttendanceRecord::create($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'تم تسجيل الحضور بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceRecord $attendance)
    {
        $attendance->load('employee');
        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceRecord $attendance)
    {
        $employees = Employee::where('employment_status', 'active')->get();
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceRecord $attendance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'تم تحديث سجل الحضور بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceRecord $attendance)
    {
        $attendance->delete();
        
        return redirect()->route('attendance.index')
            ->with('success', 'تم حذف سجل الحضور بنجاح');
    }

    /**
     * Bulk check-in for multiple employees
     */
    public function bulkCheckIn(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($validated['date'])->format('Y-m-d');
        $checkInTime = now()->format('H:i');

        foreach ($validated['employee_ids'] as $employeeId) {
            AttendanceRecord::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'date' => $date,
                ],
                [
                    'check_in' => $checkInTime,
                    'status' => 'present',
                ]
            );
        }

        return redirect()->route('attendance.index')
            ->with('success', 'تم تسجيل دخول جماعي بنجاح');
    }

    /**
     * Bulk check-out for multiple employees
     */
    public function bulkCheckOut(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($validated['date'])->format('Y-m-d');
        $checkOutTime = now()->format('H:i');

        foreach ($validated['employee_ids'] as $employeeId) {
            $record = AttendanceRecord::where('employee_id', $employeeId)
                ->where('date', $date)
                ->first();
                
            if ($record) {
                $record->update(['check_out' => $checkOutTime]);
            }
        }

        return redirect()->route('attendance.index')
            ->with('success', 'تم تسجيل خروج جماعي بنجاح');
    }

    /**
     * Monthly attendance report
     */
    public function monthlyReport($month)
    {
        $attendanceRecords = AttendanceRecord::with('employee')
            ->whereMonth('date', $month)
            ->whereYear('date', date('Y'))
            ->get();

        $stats = [
            'total_days' => $attendanceRecords->count(),
            'present' => $attendanceRecords->where('status', 'present')->count(),
            'absent' => $attendanceRecords->where('status', 'absent')->count(),
            'late' => $attendanceRecords->where('status', 'late')->count(),
            'total_hours' => $attendanceRecords->sum('hours_worked'),
            'overtime_hours' => $attendanceRecords->sum('overtime_hours'),
        ];

        return view('attendance.monthly-report', compact('attendanceRecords', 'stats', 'month'));
    }
}
