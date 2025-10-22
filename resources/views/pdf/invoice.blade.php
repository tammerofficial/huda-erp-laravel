<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
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
        .company-info {
            margin-bottom: 20px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .customer-info, .invoice-info {
            width: 48%;
        }
        .customer-info h3, .invoice-info h3 {
            margin-top: 0;
            color: #333;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .items-table .text-right {
            text-align: right;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .totals table {
            width: 100%;
        }
        .totals td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .totals .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Huda Fashion ERP</h1>
        <h2>INVOICE</h2>
    </div>

    <div class="invoice-details">
        <div class="customer-info">
            <h3>Bill To:</h3>
            <p><strong>{{ $invoice->order->customer->name }}</strong></p>
            <p>{{ $invoice->order->customer->email }}</p>
            <p>{{ $invoice->order->customer->phone }}</p>
            <p>{{ $invoice->order->customer->address }}</p>
        </div>
        
        <div class="invoice-info">
            <h3>Invoice Details:</h3>
            <p><strong>Invoice #:</strong> {{ $invoice->invoice_no }}</p>
            <p><strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</p>
            <p><strong>Status:</strong> 
                <span class="status {{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
            </p>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'N/A' }}</td>
                <td>{{ $item->product->description ?? 'N/A' }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price, 3) }} KWD</td>
                <td class="text-right">{{ number_format($item->total_price, 3) }} KWD</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ number_format($invoice->order->subtotal, 3) }} KWD</td>
            </tr>
            @if($invoice->order->shipping_cost > 0)
            <tr>
                <td>Shipping:</td>
                <td class="text-right">{{ number_format($invoice->order->shipping_cost, 3) }} KWD</td>
            </tr>
            @endif
            @if($invoice->order->tax_amount > 0)
            <tr>
                <td>Tax:</td>
                <td class="text-right">{{ number_format($invoice->order->tax_amount, 3) }} KWD</td>
            </tr>
            @endif
            <tr class="total-row">
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>{{ number_format($invoice->order->final_amount, 3) }} KWD</strong></td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    @if($invoice->notes)
    <div style="margin-top: 30px;">
        <h3>Notes:</h3>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Huda Fashion ERP - Kuwait</p>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>
</html>