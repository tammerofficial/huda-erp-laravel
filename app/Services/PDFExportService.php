<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payroll;
use App\Models\ProductionOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFExportService
{
    /**
     * Generate invoice PDF
     */
    public function generateInvoice($invoiceId)
    {
        $invoice = Invoice::with(['order.customer', 'order.items.product'])
            ->findOrFail($invoiceId);
        
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->invoice_no . '.pdf');
    }
    
    /**
     * Generate payslip PDF
     */
    public function generatePayslip($payrollId)
    {
        $payroll = Payroll::with('employee.user')->findOrFail($payrollId);
        
        $pdf = Pdf::loadView('pdf.payslip', compact('payroll'));
        
        return $pdf->download('payslip-' . $payroll->id . '.pdf');
    }
    
    /**
     * Generate production report PDF
     */
    public function generateProductionReport($orderId)
    {
        $order = ProductionOrder::with([
            'product',
            'stages.employee.user',
            'order.customer'
        ])->findOrFail($orderId);
        
        $pdf = Pdf::loadView('pdf.production-report', compact('order'));
        
        return $pdf->download('production-' . $order->id . '.pdf');
    }

    /**
     * Generate attendance report PDF
     */
    public function generateAttendanceReport($month, $year)
    {
        $attendanceRecords = \App\Models\AttendanceRecord::with('employee.user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $stats = [
            'total_days' => $attendanceRecords->count(),
            'present' => $attendanceRecords->where('status', 'present')->count(),
            'absent' => $attendanceRecords->where('status', 'absent')->count(),
            'late' => $attendanceRecords->where('status', 'late')->count(),
            'total_hours' => $attendanceRecords->sum('hours_worked'),
            'overtime_hours' => $attendanceRecords->sum('overtime_hours'),
        ];

        $pdf = Pdf::loadView('pdf.attendance-report', compact('attendanceRecords', 'stats', 'month', 'year'));
        
        return $pdf->download('attendance-report-' . $month . '-' . $year . '.pdf');
    }

    /**
     * Generate quality report PDF
     */
    public function generateQualityReport($month, $year)
    {
        $qualityChecks = \App\Models\QualityCheck::with(['productionOrder', 'product', 'inspector.user'])
            ->whereMonth('inspection_date', $month)
            ->whereYear('inspection_date', $year)
            ->get();

        $stats = [
            'total_checks' => $qualityChecks->count(),
            'passed' => $qualityChecks->where('status', 'passed')->count(),
            'failed' => $qualityChecks->where('status', 'failed')->count(),
            'pass_rate' => $qualityChecks->count() > 0 ? 
                ($qualityChecks->where('status', 'passed')->count() / $qualityChecks->count()) * 100 : 0,
        ];

        $pdf = Pdf::loadView('pdf.quality-report', compact('qualityChecks', 'stats', 'month', 'year'));
        
        return $pdf->download('quality-report-' . $month . '-' . $year . '.pdf');
    }
}
