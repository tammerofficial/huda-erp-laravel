<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>Production Report - Order #{{ $order->id }}</title>
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
        .order-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .stages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stage-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #d4af37;
        }
        .stage-title {
            font-weight: bold;
            color: #d4af37;
            margin-bottom: 10px;
        }
        .stage-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-completed { background: #d4edda; color: #155724; }
        .status-in_progress { background: #fff3cd; color: #856404; }
        .status-pending { background: #d1ecf1; color: #0c5460; }
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
        <p>Production Report - Order #{{ $order->id }}</p>
        <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
    </div>
    
    <div class="order-info">
        <h3>Order Information</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <div>
                <strong>Product:</strong> {{ $order->product->name }}<br>
                <strong>Quantity:</strong> {{ $order->quantity }} pieces<br>
                <strong>Customer:</strong> {{ $order->order->customer->name }}
            </div>
            <div>
                <strong>Start Date:</strong> {{ $order->start_date->format('Y-m-d') }}<br>
                <strong>Expected Completion:</strong> {{ $order->expected_completion_date->format('Y-m-d') }}<br>
                <strong>Status:</strong> {{ ucfirst($order->status) }}
            </div>
        </div>
    </div>
    
    <h3>Production Stages</h3>
    <div class="stages-grid">
        @foreach($order->stages as $stage)
        <div class="stage-card">
            <div class="stage-title">{{ $stage->stage_name }}</div>
            <div><strong>Employee:</strong> {{ $stage->employee ? $stage->employee->user->name : 'Not assigned' }}</div>
            <div><strong>Status:</strong> 
                <span class="stage-status status-{{ $stage->status }}">
                    {{ ucfirst($stage->status) }}
                </span>
            </div>
            @if($stage->start_time)
            <div><strong>Start:</strong> {{ $stage->start_time->format('Y-m-d H:i') }}</div>
            @endif
            @if($stage->end_time)
            <div><strong>End:</strong> {{ $stage->end_time->format('Y-m-d H:i') }}</div>
            @endif
            @if($stage->duration_minutes)
            <div><strong>Duration:</strong> {{ $stage->duration_minutes }} minutes</div>
            @endif
        </div>
        @endforeach
    </div>
    
    <h3>Production Summary</h3>
    <table>
        <thead>
            <tr>
                <th>Stage</th>
                <th>Employee</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Duration</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->stages as $stage)
            <tr>
                <td>{{ $stage->stage_name }}</td>
                <td>{{ $stage->employee ? $stage->employee->user->name : 'Not assigned' }}</td>
                <td>{{ $stage->start_time ? $stage->start_time->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $stage->end_time ? $stage->end_time->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $stage->duration_minutes ? $stage->duration_minutes . ' min' : '-' }}</td>
                <td>{{ ucfirst($stage->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($order->stages->where('status', 'completed')->count() > 0)
    <h3 style="margin-top: 30px;">Completed Stages Performance</h3>
    <table style="margin-top: 10px;">
        <thead>
            <tr>
                <th>Stage</th>
                <th>Employee</th>
                <th>Duration (min)</th>
                <th>Efficiency</th>
                <th>Quality</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->stages->where('status', 'completed') as $stage)
            <tr>
                <td>{{ $stage->stage_name }}</td>
                <td>{{ $stage->employee ? $stage->employee->user->name : 'N/A' }}</td>
                <td>{{ $stage->duration_minutes ?? 'N/A' }}</td>
                <td>{{ $stage->efficiency_rate ? number_format($stage->efficiency_rate, 1) . '%' : 'N/A' }}</td>
                <td>{{ $stage->quality_status ? ucfirst($stage->quality_status) : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <div class="footer">
        <p>This report was generated automatically by Huda Fashion ERP System</p>
        <p>For any questions, please contact Production Department</p>
    </div>
</body>
</html>
