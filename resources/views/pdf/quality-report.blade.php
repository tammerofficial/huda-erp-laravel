<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Report - {{ $month }}/{{ $year }}</title>
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
        .quality-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .quality-table th, .quality-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .quality-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .quality-table .text-right {
            text-align: right;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.passed {
            background-color: #d4edda;
            color: #155724;
        }
        .status.failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .defects-list {
            max-width: 300px;
            word-wrap: break-word;
        }
        .pass-rate {
            font-weight: bold;
            color: #28a745;
        }
        .fail-rate {
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Huda Fashion ERP</h1>
        <h2>QUALITY CONTROL REPORT</h2>
        <p>Month: {{ $month }}/{{ $year }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <h4>Total Checks</h4>
            <div class="value">{{ $stats['total_checks'] }}</div>
        </div>
        <div class="stat-item">
            <h4>Passed</h4>
            <div class="value">{{ $stats['passed'] }}</div>
        </div>
        <div class="stat-item">
            <h4>Failed</h4>
            <div class="value">{{ $stats['failed'] }}</div>
        </div>
        <div class="stat-item">
            <h4>Pass Rate</h4>
            <div class="value">{{ number_format($stats['pass_rate'], 1) }}%</div>
        </div>
    </div>

    <h3>Quality Check Details:</h3>
    <table class="quality-table">
        <thead>
            <tr>
                <th>Production Order</th>
                <th>Product</th>
                <th>Inspector</th>
                <th>Date</th>
                <th>Status</th>
                <th>Items Checked</th>
                <th>Items Passed</th>
                <th>Items Failed</th>
                <th>Pass Rate</th>
                <th>Defects</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qualityChecks as $check)
            <tr>
                <td>#{{ $check->productionOrder->id ?? 'N/A' }}</td>
                <td>{{ $check->product->name ?? 'N/A' }}</td>
                <td>{{ $check->inspector->user->name ?? 'N/A' }}</td>
                <td>{{ $check->inspection_date->format('M d, Y') }}</td>
                <td>
                    <span class="status {{ $check->status }}">{{ ucfirst($check->status) }}</span>
                </td>
                <td class="text-right">{{ $check->items_checked }}</td>
                <td class="text-right">{{ $check->items_passed }}</td>
                <td class="text-right">{{ $check->items_failed }}</td>
                <td class="text-right">
                    @if($check->items_checked > 0)
                        @php
                            $passRate = round(($check->items_passed / $check->items_checked) * 100, 1);
                        @endphp
                        <span class="{{ $passRate >= 90 ? 'pass-rate' : 'fail-rate' }}">
                            {{ $passRate }}%
                        </span>
                    @else
                        N/A
                    @endif
                </td>
                <td class="defects-list">
                    @if($check->defects && count($check->defects) > 0)
                        @foreach($check->defects as $defect => $description)
                            <div><strong>{{ $defect }}:</strong> {{ $description }}</div>
                        @endforeach
                    @else
                        None
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <h3>Quality Analysis:</h3>
        
        @if($stats['total_checks'] > 0)
        <div style="margin: 20px 0;">
            <h4>Overall Performance:</h4>
            <p><strong>Total Quality Checks:</strong> {{ $stats['total_checks'] }}</p>
            <p><strong>Pass Rate:</strong> 
                <span style="color: {{ $stats['pass_rate'] >= 90 ? 'green' : ($stats['pass_rate'] >= 80 ? 'orange' : 'red') }}; font-weight: bold;">
                    {{ number_format($stats['pass_rate'], 1) }}%
                </span>
            </p>
            
            @if($stats['pass_rate'] >= 90)
                <p style="color: green; font-weight: bold;">✅ Excellent quality performance!</p>
            @elseif($stats['pass_rate'] >= 80)
                <p style="color: orange; font-weight: bold;">⚠️ Good quality performance, room for improvement.</p>
            @else
                <p style="color: red; font-weight: bold;">❌ Quality performance needs immediate attention.</p>
            @endif
        </div>

        <div style="margin: 20px 0;">
            <h4>Common Defects:</h4>
            @php
                $allDefects = $qualityChecks->pluck('defects')->filter()->flatten();
                $defectCounts = $allDefects->countBy();
                $topDefects = $defectCounts->sortDesc()->take(5);
            @endphp
            
            @if($topDefects->count() > 0)
                <ul>
                    @foreach($topDefects as $defect => $count)
                        <li><strong>{{ $defect }}:</strong> {{ $count }} occurrences</li>
                    @endforeach
                </ul>
            @else
                <p>No defects recorded in this period.</p>
            @endif
        </div>

        <div style="margin: 20px 0;">
            <h4>Inspector Performance:</h4>
            @php
                $inspectorStats = $qualityChecks->groupBy('inspector_id')->map(function($checks) {
                    $total = $checks->count();
                    $passed = $checks->where('status', 'passed')->count();
                    $passRate = $total > 0 ? round(($passed / $total) * 100, 1) : 0;
                    
                    return [
                        'inspector' => $checks->first()->inspector,
                        'total' => $total,
                        'passed' => $passed,
                        'pass_rate' => $passRate
                    ];
                });
            @endphp
            
            <table class="quality-table" style="width: 60%;">
                <thead>
                    <tr>
                        <th>Inspector</th>
                        <th>Total Checks</th>
                        <th>Passed</th>
                        <th>Pass Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inspectorStats as $stat)
                    <tr>
                        <td>{{ $stat['inspector']->user->name ?? 'N/A' }}</td>
                        <td class="text-right">{{ $stat['total'] }}</td>
                        <td class="text-right">{{ $stat['passed'] }}</td>
                        <td class="text-right">
                            <span style="color: {{ $stat['pass_rate'] >= 90 ? 'green' : ($stat['pass_rate'] >= 80 ? 'orange' : 'red') }}; font-weight: bold;">
                                {{ $stat['pass_rate'] }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>No quality checks recorded for this period.</p>
        @endif
    </div>

    <div class="footer">
        <p>This report was generated automatically by Huda Fashion ERP</p>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>
</html>