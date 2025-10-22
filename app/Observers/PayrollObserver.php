<?php

namespace App\Observers;

use App\Models\Payroll;
use App\Services\AccountingService;

class PayrollObserver
{
    /**
     * Handle the Payroll "created" event.
     */
    public function created(Payroll $payroll): void
    {
        // Auto-create accounting entry when payroll is created
        if ($payroll->status === 'approved') {
            $this->createAccountingEntry($payroll);
        }
    }

    /**
     * Handle the Payroll "updated" event.
     */
    public function updated(Payroll $payroll): void
    {
        // Create accounting entry when payroll is approved
        if ($payroll->wasChanged('status') && $payroll->status === 'approved') {
            $this->createAccountingEntry($payroll);
        }
        
        // Create payment entry when payroll is marked as paid
        if ($payroll->wasChanged('status') && $payroll->status === 'paid') {
            $this->createPaymentEntry($payroll);
        }
    }

    /**
     * Create accounting entry for payroll
     */
    protected function createAccountingEntry(Payroll $payroll): void
    {
        try {
            $accountingService = app(AccountingService::class);
            
            $accountingService->recordPayrollExpense(
                $payroll->employee,
                $payroll->total_amount,
                $payroll->period_start,
                'payroll_expense',
                $payroll->id
            );
        } catch (\Exception $e) {
            \Log::error('Failed to create accounting entry for payroll: ' . $e->getMessage());
        }
    }

    /**
     * Create payment entry for payroll
     */
    protected function createPaymentEntry(Payroll $payroll): void
    {
        try {
            $accountingService = app(AccountingService::class);
            
            $accountingService->recordPayment(
                'payroll_payment',
                $payroll->total_amount,
                $payroll->payment_date ?? now(),
                'Payroll payment for ' . $payroll->employee->user->name,
                $payroll->id
            );
        } catch (\Exception $e) {
            \Log::error('Failed to create payment entry for payroll: ' . $e->getMessage());
        }
    }
}
