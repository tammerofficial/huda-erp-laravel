<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>Payslip #{{ $payroll->id }}</title>
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
        .employee-info { 
            margin: 20px 0; 
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .salary-details {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .salary-breakdown {
            width: 48%;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .deductions {
            width: 48%;
            background: #fff3cd;
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
            background-color: #d4af37;
            color: white;
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
        <p>كشف راتب - {{ $payroll->period_start->format('Y-m-d') }} إلى {{ $payroll->period_end->format('Y-m-d') }}</p>
    </div>
    
    <div class="employee-info">
        <p><strong>اسم الموظف:</strong> {{ $payroll->employee->user->name }}</p>
        <p><strong>رقم الموظف:</strong> {{ $payroll->employee->employee_id }}</p>
        <p><strong>المنصب:</strong> {{ $payroll->employee->position }}</p>
        <p><strong>القسم:</strong> {{ $payroll->employee->department }}</p>
    </div>
    
    <div class="salary-details">
        <div class="salary-breakdown">
            <h3>الراتب والمكافآت</h3>
            <table>
                <tr>
                    <td>الراتب الأساسي</td>
                    <td>{{ number_format($payroll->base_salary, 3) }} KWD</td>
                </tr>
                <tr>
                    <td>ساعات الإضافي</td>
                    <td>{{ number_format($payroll->overtime_hours, 2) }} ساعة</td>
                </tr>
                <tr>
                    <td>مبلغ الإضافي</td>
                    <td>{{ number_format($payroll->overtime_amount, 3) }} KWD</td>
                </tr>
                <tr>
                    <td>المكافآت</td>
                    <td>{{ number_format($payroll->bonuses, 3) }} KWD</td>
                </tr>
            </table>
        </div>
        
        <div class="deductions">
            <h3>الخصومات</h3>
            <table>
                <tr>
                    <td>الخصومات</td>
                    <td>{{ number_format($payroll->deductions, 3) }} KWD</td>
                </tr>
            </table>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>الوصف</th>
                <th>المبلغ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>الراتب الأساسي</td>
                <td>{{ number_format($payroll->base_salary, 3) }} KWD</td>
            </tr>
            <tr>
                <td>الإضافي</td>
                <td>{{ number_format($payroll->overtime_amount, 3) }} KWD</td>
            </tr>
            <tr>
                <td>المكافآت</td>
                <td>{{ number_format($payroll->bonuses, 3) }} KWD</td>
            </tr>
            <tr>
                <td>الخصومات</td>
                <td>-{{ number_format($payroll->deductions, 3) }} KWD</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total">
                <td>الإجمالي</td>
                <td>{{ number_format($payroll->total_amount, 3) }} KWD</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>تم إنشاء هذا الكشف في: {{ now()->format('Y-m-d H:i') }}</p>
        <p>حالة الكشف: {{ ucfirst($payroll->status) }}</p>
    </div>
</body>
</html>
