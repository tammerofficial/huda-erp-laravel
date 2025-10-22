<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Check Required</title>
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
            background-color: #17a2b8;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
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
        .status.awaiting_quality_check {
            background-color: #fff3cd;
            color: #856404;
        }
        .urgency {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Quality Check Required</h1>
            <p>Production order awaiting quality inspection</p>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <h3>📋 Quality Inspection Required</h3>
                <p>The following production order has been completed and is awaiting quality inspection:</p>
            </div>
            
            @if($order->created_at->lt(now()->subHours(24)))
            <div class="urgency">
                <h3>🚨 Urgent Attention Required</h3>
                <p>This production order has been waiting for quality inspection for more than 24 hours. Please prioritize this inspection to avoid further delays.</p>
            </div>
            @endif
            
            <div class="order-info">
                <h3>🏭 Production Order Details</h3>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Product:</strong> {{ $order->product->name ?? 'N/A' }}</p>
                <p><strong>Quantity:</strong> {{ $order->quantity_to_produce }}</p>
                <p><strong>Status:</strong> 
                    <span class="status awaiting_quality_check">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                </p>
                <p><strong>Production Completed:</strong> {{ $order->updated_at->format('M d, Y H:i') }}</p>
                <p><strong>Waiting Time:</strong> {{ $order->updated_at->diffForHumans() }}</p>
            </div>
            
            <div style="margin: 30px 0;">
                <h3>📋 Quality Inspection Checklist:</h3>
                <ul>
                    <li>✅ Visual inspection of all items</li>
                    <li>✅ Check measurements and dimensions</li>
                    <li>✅ Verify stitching quality</li>
                    <li>✅ Test functionality (if applicable)</li>
                    <li>✅ Check for defects or damage</li>
                    <li>✅ Verify quantity matches order</li>
                    <li>✅ Document any issues found</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('quality-checks.inspect', $order) }}" class="button">
                    Start Quality Inspection
                </a>
                <a href="{{ route('quality-checks.index') }}" class="button" style="background-color: #28a745;">
                    View All Quality Checks
                </a>
            </div>
            
            <div style="background-color: #e9ecef; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4>💡 Quality Tips:</h4>
                <ul style="margin: 10px 0;">
                    <li>Use proper lighting for inspection</li>
                    <li>Check both front and back of items</li>
                    <li>Test all moving parts or closures</li>
                    <li>Document any defects with photos</li>
                    <li>Follow standard quality procedures</li>
                </ul>
            </div>
            
            <div style="background-color: #d4edda; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4>📊 Quality Standards:</h4>
                <p>Please ensure all items meet our quality standards before approval. Any items that don't meet standards should be marked as failed with detailed notes about the issues found.</p>
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
