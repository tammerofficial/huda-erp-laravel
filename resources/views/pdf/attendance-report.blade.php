<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>Attendance Report - {{ $month }}/{{ $year }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            margin: 0;
            padding: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 20px;
        }
        .company-info {
            font-size: 18px;
            font-weight: bold;
            color: #d4af37;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #d4af37;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: right; 
        }
        th { 
            background-color: #d4af37; 
            color: white;
            font-weight: bold;
        }
        .status-present { color: #28a745; }
        .status-absent { color: #dc3545; }
        .status-late { color: #ffc107; }
        .status-half-day { color: #17a2b8; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">Huda Fashion</div>
        <p>Attendance Report - {{ $month }}/{{ $year }}</p>
        <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_days'] }}</div>
            <div class="stat-label">Total Days</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['present'] }}</div>
            <div class="stat-label">Present</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['absent'] }}</div>
            <div class="stat-label">Absent</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['late'] }}</div>
            <div class="stat-label">Late</div>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_hours'], 1) }}h</div>
            <div class="stat-label">Total Hours</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['overtime_hours'], 1) }}h</div>
            <div class="stat-label">Overtime Hours</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_days'] > 0 ? round(($stats['present'] / $stats['total_days']) * 100, 1) : 0 }}%</div>
            <div class="stat-label">Attendance Rate</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_days'] > 0 ? round(($stats['late'] / $stats['total_days']) * 100, 1) : 0 }}%</div>
            <div class="stat-label">Late Rate</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Hours</th>
                <th>Overtime</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceRecords as $record)
            <tr>
                <td>{{ $record->employee->user->name }}</td>
                <td>{{ $record->date->format('Y-m-d') }}</td>
                <td>{{ $record->check_in ? $record->check_in->format('H:i') : '-' }}</td>
                <td>{{ $record->check_out ? $record->check_out->format('H:i') : '-' }}</td>
                <td>{{ number_format($record->hours_worked, 1) }}h</td>
                <td>{{ number_format($record->overtime_hours, 1) }}h</td>
                <td class="status-{{ $record->status }}">
                    @if($record->status === 'present') Present
                    @elseif($record->status === 'absent') Absent
                    @elseif($record->status === 'late') Late
                    @elseif($record->status === 'half_day') Half Day
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>This report was generated automatically by Huda Fashion ERP System</p>
        <p>For any questions, please contact HR Department</p>
    </div>
</body>
</html>
