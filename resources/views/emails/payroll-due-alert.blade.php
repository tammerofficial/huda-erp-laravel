<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Due Alert</title>
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
            background-color: #6f42c1;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background-color: #e2e3e5;
            border: 1px solid #d6d8db;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .payroll-info {
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
        .status.approved {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .employee-list {
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
            <h1>💰 Payroll Due Alert</h1>
            <p>Approved payroll awaiting payment</p>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <h3>📅 Payroll Payment Due</h3>
                <p>The following payroll has been approved and is due for payment:</p>
            </div>
            
            <div class="payroll-info">
                <h3>👤 Employee Information</h3>
                <p><strong>Employee:</strong> {{ $payroll->employee->user->name }}</p>
                <p><strong>Employee ID:</strong> {{ $payroll->employee->employee_id }}</p>
                <p><strong>Position:</strong> {{ $payroll->employee->position }}</p>
                <p><strong>Department:</strong> {{ $payroll->employee->department }}</p>
            </div>
            
            <div class="payroll-info">
                <h3>💰 Payroll Details</h3>
                <p><strong>Pay Period:</strong> {{ $payroll->period_start->format('M d, Y') }} - {{ $payroll->period_end->format('M d, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status approved">{{ ucfirst($payroll->status) }}</span>
                </p>
                <p><strong>Total Amount:</strong> 
                    <span class="amount">{{ number_format($payroll->total_amount, 3) }} KWD</span>
                </p>
                <p><strong>Payment Method:</strong> {{ $payroll->payment_method ?? 'Not specified' }}</p>
                <p><strong>Approved On:</strong> {{ $payroll->updated_at->format('M d, Y H:i') }}</p>
            </div>
            
            <div class="employee-list">
                <h4>📊 Payroll Breakdown:</h4>
                <ul>
                    <li><strong>Base Salary:</strong> {{ number_format($payroll->base_salary, 3) }} KWD</li>
                    @if($payroll->overtime_hours > 0)
                        <li><strong>Overtime ({{ $payroll->overtime_hours }} hours):</strong> {{ number_format($payroll->overtime_amount, 3) }} KWD</li>
                    @endif
                    @if($payroll->bonuses > 0)
                        <li><strong>Bonuses:</strong> {{ number_format($payroll->bonuses, 3) }} KWD</li>
                    @endif
                    @if($payroll->deductions > 0)
                        <li><strong>Deductions:</strong> -{{ number_format($payroll->deductions, 3) }} KWD</li>
                    @endif
                </ul>
            </div>
            
            <div style="margin: 30px 0;">
                <h3>📋 Payment Process:</h3>
                <ol>
                    <li>Verify payroll details and amounts</li>
                    <li>Process payment through selected method</li>
                    <li>Update payment status in system</li>
                    <li>Send payslip to employee</li>
                    <li>Record payment in accounting system</li>
                </ol>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('payroll.show', $payroll) }}" class="button">
                    View Payroll Details
                </a>
                <a href="{{ route('payroll.index') }}" class="button" style="background-color: #28a745;">
                    View All Payrolls
                </a>
            </div>
            
            <div style="background-color: #e9ecef; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4>💡 Payment Tips:</h4>
                <ul style="margin: 10px 0;">
                    <li>Verify bank account details before processing</li>
                    <li>Ensure sufficient funds are available</li>
                    <li>Process payments during business hours</li>
                    <li>Keep payment records for accounting</li>
                    <li>Send confirmation to employee</li>
                </ul>
            </div>
            
            <div style="background-color: #d4edda; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4>📊 Compliance Reminder:</h4>
                <p>Please ensure all payroll payments are processed in accordance with local labor laws and company policies. Keep detailed records for audit purposes.</p>
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
