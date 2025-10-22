<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->employee->user->name }}</title>
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
        .employee-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .employee-details, .payroll-details {
            width: 48%;
        }
        .employee-details h3, .payroll-details h3 {
            margin-top: 0;
            color: #333;
        }
        .earnings-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .earnings-table th, .earnings-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .earnings-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .earnings-table .text-right {
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
            font-size: 16px;
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
        .status.approved {
            background-color: #d1ecf1;
            color: #0c5460;
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
        <h2>PAYSLIP</h2>
    </div>

    <div class="employee-info">
        <div class="employee-details">
            <h3>Employee Information:</h3>
            <p><strong>Name:</strong> {{ $payroll->employee->user->name }}</p>
            <p><strong>Employee ID:</strong> {{ $payroll->employee->employee_id }}</p>
            <p><strong>Position:</strong> {{ $payroll->employee->position }}</p>
            <p><strong>Department:</strong> {{ $payroll->employee->department }}</p>
        </div>
        
        <div class="payroll-details">
            <h3>Payroll Details:</h3>
            <p><strong>Pay Period:</strong> {{ $payroll->period_start->format('M d, Y') }} - {{ $payroll->period_end->format('M d, Y') }}</p>
            <p><strong>Payment Date:</strong> {{ $payroll->payment_date ? $payroll->payment_date->format('M d, Y') : 'Not paid yet' }}</p>
            <p><strong>Status:</strong> 
                <span class="status {{ $payroll->status }}">{{ ucfirst($payroll->status) }}</span>
            </p>
            <p><strong>Payment Method:</strong> {{ $payroll->payment_method ?? 'N/A' }}</p>
        </div>
    </div>

    <table class="earnings-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount (KWD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Base Salary</td>
                <td class="text-right">{{ number_format($payroll->base_salary, 3) }}</td>
            </tr>
            @if($payroll->overtime_hours > 0)
            <tr>
                <td>Overtime ({{ $payroll->overtime_hours }} hours)</td>
                <td class="text-right">{{ number_format($payroll->overtime_amount, 3) }}</td>
            </tr>
            @endif
            @if($payroll->bonuses > 0)
            <tr>
                <td>Bonuses</td>
                <td class="text-right">{{ number_format($payroll->bonuses, 3) }}</td>
            </tr>
            @endif
            @if($payroll->deductions > 0)
            <tr>
                <td>Deductions</td>
                <td class="text-right">-{{ number_format($payroll->deductions, 3) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr class="total-row">
                <td><strong>Net Pay:</strong></td>
                <td class="text-right"><strong>{{ number_format($payroll->total_amount, 3) }} KWD</strong></td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    @if($payroll->notes)
    <div style="margin-top: 30px;">
        <h3>Notes:</h3>
        <p>{{ $payroll->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>This payslip is computer generated and does not require a signature.</p>
        <p>Huda Fashion ERP - Kuwait</p>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>
</html>