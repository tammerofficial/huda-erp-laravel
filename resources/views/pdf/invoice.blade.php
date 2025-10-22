<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
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
        .invoice-details { 
            margin: 20px 0; 
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
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
        .total { 
            font-size: 18px; 
            font-weight: bold; 
            background-color: #f8f9fa;
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
        <p>فاتورة رقم: {{ $invoice->invoice_no }}</p>
        <p>تاريخ الإصدار: {{ $invoice->issue_date->format('Y-m-d') }}</p>
    </div>
    
    <div class="invoice-details">
        <p><strong>العميل:</strong> {{ $invoice->order->customer->name }}</p>
        <p><strong>رقم الطلب:</strong> {{ $invoice->order->order_number }}</p>
        <p><strong>تاريخ الطلب:</strong> {{ $invoice->order->order_date->format('Y-m-d') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 3) }} KWD</td>
                <td>{{ number_format($item->total, 3) }} KWD</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="3">الإجمالي</td>
                <td>{{ number_format($invoice->total_amount, 3) }} KWD</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>شكراً لاختياركم Huda Fashion</p>
        <p>هاتف: +965 XXXX XXXX | البريد الإلكتروني: info@hudafashion.com</p>
    </div>
</body>
</html>
