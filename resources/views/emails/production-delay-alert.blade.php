<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Delay Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #fd7e14;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .order-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.delayed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .timeline {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Production Delay Alert</h1>
            <p>Production order is behind schedule</p>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <h3>⚠️ Production Delay Warning</h3>
                <p>The following production order is behind schedule and requires immediate attention:</p>
            </div>
            
            <div class="order-info">
                <h3>🏭 Production Order Details</h3>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Product:</strong> {{ $order->product->name ?? 'N/A' }}</p>
                <p><strong>Quantity:</strong> {{ $order->quantity_to_produce }}</p>
                <p><strong>Status:</strong> 
                    <span class="status delayed">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                </p>
                <p><strong>Start Date:</strong> {{ $order->start_date ? $order->start_date->format('M d, Y') : 'Not started' }}</p>
                <p><strong>Expected Completion:</strong> {{ $order->expected_completion_date ? $order->expected_completion_date->format('M d, Y') : 'N/A' }}</p>
                <p><strong>Days Overdue:</strong> 
                    @if($order->expected_completion_date && $order->expected_completion_date->isPast())
                        {{ $order->expected_completion_date->diffInDays(now()) }} days
                    @else
                        N/A
                    @endif
                </p>
            </div>
            
            <div class="timeline">
                <h4>📅 Timeline Analysis:</h4>
                <ul>
                    <li><strong>Order Created:</strong> {{ $order->created_at->format('M d, Y H:i') }}</li>
                    @if($order->start_date)
                        <li><strong>Production Started:</strong> {{ $order->start_date->format('M d, Y H:i') }}</li>
                    @endif
                    @if($order->expected_completion_date)
                        <li><strong>Expected Completion:</strong> {{ $order->expected_completion_date->format('M d, Y H:i') }}</li>
                    @endif
                    <li><strong>Current Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</li>
                </ul>
            </div>
            
            <div style="margin: 30px 0;">
                <h3>📋 Recommended Actions:</h3>
                <ul>
                    <li>Review production stages and identify bottlenecks</li>
                    <li>Reassign resources to critical stages</li>
                    <li>Consider overtime or additional shifts</li>
                    <li>Communicate with customer about potential delays</li>
                    <li>Update production schedule and priorities</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('productions.show', $order) }}" class="button">
                    View Production Order
                </a>
                <a href="{{ route('productions.index') }}" class="button" style="background-color: #28a745;">
                    View All Production Orders
                </a>
            </div>
            
            <div style="background-color: #e9ecef; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4>💡 Quick Tips:</h4>
                <ul style="margin: 10px 0;">
                    <li>Check if all required materials are available</li>
                    <li>Review employee availability and skills</li>
                    <li>Consider outsourcing non-critical stages</li>
                    <li>Update customer expectations if necessary</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p>This alert was generated automatically by Huda Fashion ERP</p>
            <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
            <p>Huda Fashion ERP - Kuwait</p>
        </div>
    </div>
</body>
</html>
