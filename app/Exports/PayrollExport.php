<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayrollExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $month;
    protected $year;
    
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }
    
    public function collection()
    {
        return \App\Models\Payroll::with('employee.user')
            ->whereMonth('period_start', $this->month)
            ->whereYear('period_start', $this->year)
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee ID',
            'Base Salary',
            'Overtime Hours',
            'Overtime Amount',
            'Bonuses',
            'Deductions',
            'Total Amount',
            'Status',
            'Period Start',
            'Period End'
        ];
    }
    
    public function map($payroll): array
    {
        return [
            $payroll->employee->user->name,
            $payroll->employee->employee_id,
            number_format($payroll->base_salary, 3),
            number_format($payroll->overtime_hours, 2),
            number_format($payroll->overtime_amount, 3),
            number_format($payroll->bonuses, 3),
            number_format($payroll->deductions, 3),
            number_format($payroll->total_amount, 3),
            ucfirst($payroll->status),
            $payroll->period_start->format('Y-m-d'),
            $payroll->period_end->format('Y-m-d'),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
