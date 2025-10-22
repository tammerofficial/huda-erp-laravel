<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        return \App\Models\AttendanceRecord::with('employee.user')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee ID',
            'Date',
            'Check In',
            'Check Out',
            'Hours Worked',
            'Overtime Hours',
            'Status',
            'Notes'
        ];
    }
    
    public function map($record): array
    {
        return [
            $record->employee->user->name,
            $record->employee->employee_id,
            $record->date->format('Y-m-d'),
            $record->check_in ?? '-',
            $record->check_out ?? '-',
            number_format($record->hours_worked, 2),
            number_format($record->overtime_hours, 2),
            ucfirst($record->status),
            $record->notes ?? '-',
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
