<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report - {{ $month }}/{{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .stat-item {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin: 10px;
            min-width: 150px;
        }
        .stat-item h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .stat-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .attendance-table th, .attendance-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .attendance-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .attendance-table .text-right {
            text-align: right;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.present {
            background-color: #d4edda;
            color: #155724;
        }
        .status.absent {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status.late {
            background-color: #fff3cd;
            color: #856404;
        }
        .status.half_day {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Huda Fashion ERP</h1>
        <h2>ATTENDANCE REPORT</h2>
        <p>Month: {{ $month }}/{{ $year }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <h4>Total Records</h4>
            <div class="value">{{ $stats['total_days'] }}</div>
        </div>
        <div class="stat-item">
            <h4>Present</h4>
            <div class="value">{{ $stats['present'] }}</div>
        </div>
        <div class="stat-item">
            <h4>Absent</h4>
            <div class="value">{{ $stats['absent'] }}</div>
        </div>
        <div class="stat-item">
            <h4>Late</h4>
            <div class="value">{{ $stats['late'] }}</div>
        </div>
        <div class="stat-item">
            <h4>Total Hours</h4>
            <div class="value">{{ number_format($stats['total_hours'], 1) }}</div>
        </div>
        <div class="stat-item">
            <h4>Overtime Hours</h4>
            <div class="value">{{ number_format($stats['overtime_hours'], 1) }}</div>
        </div>
    </div>

    <h3>Attendance Records:</h3>
    <table class="attendance-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Hours Worked</th>
                <th>Overtime</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceRecords as $record)
            <tr>
                <td>{{ $record->employee->user->name }}</td>
                <td>{{ $record->date->format('M d, Y') }}</td>
                <td>{{ $record->check_in ?? 'N/A' }}</td>
                <td>{{ $record->check_out ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($record->hours_worked, 2) }}</td>
                <td class="text-right">{{ number_format($record->overtime_hours, 2) }}</td>
                <td>
                    <span class="status {{ $record->status }}">{{ ucfirst($record->status) }}</span>
                </td>
                <td>{{ $record->notes ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <h3>Employee Summary:</h3>
    <table class="attendance-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Total Days</th>
                <th>Present Days</th>
                <th>Absent Days</th>
                <th>Late Days</th>
                <th>Total Hours</th>
                <th>Overtime Hours</th>
                <th>Attendance Rate</th>
            </tr>
        </thead>
        <tbody>
            @php
                $employeeStats = $attendanceRecords->groupBy('employee_id')->map(function($records) {
                    $totalDays = $records->count();
                    $presentDays = $records->where('status', 'present')->count();
                    $absentDays = $records->where('status', 'absent')->count();
                    $lateDays = $records->where('status', 'late')->count();
                    $totalHours = $records->sum('hours_worked');
                    $overtimeHours = $records->sum('overtime_hours');
                    $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
                    
                    return [
                        'employee' => $records->first()->employee,
                        'total_days' => $totalDays,
                        'present_days' => $presentDays,
                        'absent_days' => $absentDays,
                        'late_days' => $lateDays,
                        'total_hours' => $totalHours,
                        'overtime_hours' => $overtimeHours,
                        'attendance_rate' => $attendanceRate
                    ];
                });
            @endphp
            
            @foreach($employeeStats as $stat)
            <tr>
                <td>{{ $stat['employee']->user->name }}</td>
                <td class="text-right">{{ $stat['total_days'] }}</td>
                <td class="text-right">{{ $stat['present_days'] }}</td>
                <td class="text-right">{{ $stat['absent_days'] }}</td>
                <td class="text-right">{{ $stat['late_days'] }}</td>
                <td class="text-right">{{ number_format($stat['total_hours'], 1) }}</td>
                <td class="text-right">{{ number_format($stat['overtime_hours'], 1) }}</td>
                <td class="text-right">
                    <span style="color: {{ $stat['attendance_rate'] >= 90 ? 'green' : ($stat['attendance_rate'] >= 80 ? 'orange' : 'red') }}">
                        {{ $stat['attendance_rate'] }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by Huda Fashion ERP</p>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>
</html>