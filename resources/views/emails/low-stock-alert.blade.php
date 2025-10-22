<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
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
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .material-info {
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
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Low Stock Alert</h1>
            <p>Immediate attention required</p>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <h3>⚠️ Low Stock Warning</h3>
                <p>The following material has reached its reorder level and requires immediate attention:</p>
            </div>
            
            <div class="material-info">
                <h3>📦 Material Details</h3>
                <p><strong>Material Name:</strong> {{ $material->name }}</p>
                <p><strong>SKU:</strong> {{ $material->sku }}</p>
                <p><strong>Current Stock:</strong> 
                    <span style="color: #dc3545; font-weight: bold;">
                        {{ $material->current_stock ?? 'Unknown' }} {{ $material->unit }}
                    </span>
                </p>
                <p><strong>Reorder Level:</strong> {{ $material->reorder_level }} {{ $material->unit }}</p>
                <p><strong>Unit Cost:</strong> {{ number_format($material->unit_cost, 3) }} KWD</p>
                <p><strong>Category:</strong> {{ $material->category }}</p>
            </div>
            
            <div style="margin: 30px 0;">
                <h3>📋 Recommended Actions:</h3>
                <ul>
                    <li>Check current inventory levels</li>
                    <li>Place purchase order with supplier</li>
                    <li>Update reorder levels if needed</li>
                    <li>Consider alternative suppliers</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('materials.show', $material) }}" class="button">
                    View Material Details
                </a>
                <a href="{{ route('materials.low-stock') }}" class="button" style="background-color: #28a745;">
                    View All Low Stock Items
                </a>
            </div>
            
            <div style="background-color: #e9ecef; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4>💡 Quick Tips:</h4>
                <ul style="margin: 10px 0;">
                    <li>Set up automatic reorder notifications</li>
                    <li>Review supplier lead times</li>
                    <li>Consider bulk purchasing for better rates</li>
                    <li>Monitor usage patterns to optimize reorder levels</li>
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
