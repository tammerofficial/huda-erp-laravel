<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>Quality Report - {{ $month }}/{{ $year }}</title>
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
        .status-passed { color: #28a745; }
        .status-failed { color: #dc3545; }
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
        <p>Quality Control Report - {{ $month }}/{{ $year }}</p>
        <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_checks'] }}</div>
            <div class="stat-label">Total Checks</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['passed'] }}</div>
            <div class="stat-label">Passed</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['failed'] }}</div>
            <div class="stat-label">Failed</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['pass_rate'], 1) }}%</div>
            <div class="stat-label">Pass Rate</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Check ID</th>
                <th>Product</th>
                <th>Inspector</th>
                <th>Date</th>
                <th>Items Checked</th>
                <th>Items Passed</th>
                <th>Items Failed</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qualityChecks as $check)
            <tr>
                <td>#{{ $check->id }}</td>
                <td>{{ $check->product->name }}</td>
                <td>{{ $check->inspector->user->name }}</td>
                <td>{{ $check->inspection_date->format('Y-m-d') }}</td>
                <td>{{ $check->items_checked }}</td>
                <td>{{ $check->items_passed }}</td>
                <td>{{ $check->items_failed }}</td>
                <td class="status-{{ $check->status }}">
                    @if($check->status === 'passed') Passed
                    @else Failed
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($qualityChecks->where('status', 'failed')->count() > 0)
    <h3 style="margin-top: 30px; color: #dc3545;">Failed Quality Checks Details</h3>
    <table style="margin-top: 10px;">
        <thead>
            <tr>
                <th>Check ID</th>
                <th>Product</th>
                <th>Defects</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qualityChecks->where('status', 'failed') as $check)
            <tr>
                <td>#{{ $check->id }}</td>
                <td>{{ $check->product->name }}</td>
                <td>
                    @if($check->defects)
                        @foreach($check->defects as $defect)
                            <div>{{ $defect }}</div>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>{{ $check->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <div class="footer">
        <p>This report was generated automatically by Huda Fashion ERP System</p>
        <p>For any questions, please contact Quality Control Department</p>
    </div>
</body>
</html>
