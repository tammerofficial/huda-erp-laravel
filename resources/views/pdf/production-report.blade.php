<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Report - Order #{{ $order->id }}</title>
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
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .order-details, .product-details {
            width: 48%;
        }
        .order-details h3, .product-details h3 {
            margin-top: 0;
            color: #333;
        }
        .stages-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .stages-table th, .stages-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .stages-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .stages-table .text-right {
            text-align: right;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status.in_progress {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .summary-item {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .summary-item h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .summary-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Huda Fashion ERP</h1>
        <h2>PRODUCTION REPORT</h2>
    </div>

    <div class="order-info">
        <div class="order-details">
            <h3>Production Order Information:</h3>
            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
            <p><strong>Product:</strong> {{ $order->product->name }}</p>
            <p><strong>Quantity:</strong> {{ $order->quantity_to_produce }}</p>
            <p><strong>Status:</strong> 
                <span class="status {{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
            </p>
            <p><strong>Start Date:</strong> {{ $order->start_date ? $order->start_date->format('M d, Y') : 'N/A' }}</p>
            <p><strong>Expected Completion:</strong> {{ $order->expected_completion_date ? $order->expected_completion_date->format('M d, Y') : 'N/A' }}</p>
        </div>
        
        <div class="product-details">
            <h3>Product Details:</h3>
            <p><strong>SKU:</strong> {{ $order->product->sku }}</p>
            <p><strong>Category:</strong> {{ $order->product->category }}</p>
            <p><strong>Description:</strong> {{ $order->product->description ?? 'N/A' }}</p>
            <p><strong>Customer:</strong> {{ $order->order->customer->name ?? 'N/A' }}</p>
        </div>
    </div>

    <h3>Production Stages:</h3>
    <table class="stages-table">
        <thead>
            <tr>
                <th>Stage</th>
                <th>Employee</th>
                <th>Status</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Duration</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->stages as $stage)
            <tr>
                <td>{{ $stage->stage_name }}</td>
                <td>{{ $stage->employee->user->name ?? 'Not assigned' }}</td>
                <td>
                    <span class="status {{ $stage->status }}">{{ ucfirst($stage->status) }}</span>
                </td>
                <td>{{ $stage->start_time ? $stage->start_time->format('M d, Y H:i') : 'N/A' }}</td>
                <td>{{ $stage->end_time ? $stage->end_time->format('M d, Y H:i') : 'N/A' }}</td>
                <td>
                    @if($stage->start_time && $stage->end_time)
                        {{ $stage->start_time->diffInMinutes($stage->end_time) }} minutes
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $stage->notes ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <h4>Total Stages</h4>
            <div class="value">{{ $order->stages->count() }}</div>
        </div>
        <div class="summary-item">
            <h4>Completed Stages</h4>
            <div class="value">{{ $order->stages->where('status', 'completed')->count() }}</div>
        </div>
        <div class="summary-item">
            <h4>In Progress</h4>
            <div class="value">{{ $order->stages->where('status', 'in_progress')->count() }}</div>
        </div>
        <div class="summary-item">
            <h4>Completion Rate</h4>
            <div class="value">
                @if($order->stages->count() > 0)
                    {{ round(($order->stages->where('status', 'completed')->count() / $order->stages->count()) * 100, 1) }}%
                @else
                    0%
                @endif
            </div>
        </div>
    </div>

    @if($order->stages->where('status', 'completed')->count() > 0)
    <div style="margin-top: 30px;">
        <h3>Production Summary:</h3>
        <p><strong>Total Production Time:</strong> 
            @php
                $totalMinutes = 0;
                foreach($order->stages->where('status', 'completed') as $stage) {
                    if($stage->start_time && $stage->end_time) {
                        $totalMinutes += $stage->start_time->diffInMinutes($stage->end_time);
                    }
                }
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
            @endphp
            {{ $hours }} hours {{ $minutes }} minutes
        </p>
        <p><strong>Average Stage Time:</strong> 
            @if($order->stages->where('status', 'completed')->count() > 0)
                {{ round($totalMinutes / $order->stages->where('status', 'completed')->count(), 1) }} minutes
            @else
                N/A
            @endif
        </p>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by Huda Fashion ERP</p>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>
</html>