<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payroll;
use App\Models\Accounting;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Record order revenue
     */
    public function recordOrderRevenue(Order $order): Accounting
    {
        return Accounting::create([
            'date' => $order->order_date,
            'type' => 'revenue',
            'category' => 'sales',
            'amount' => $order->final_amount - ($order->shipping_cost ?? 0),
            'description' => "Sales revenue from Order #{$order->order_number}",
            'reference_type' => 'order',
            'reference_id' => $order->id,
            'created_by' => $order->created_by ?? 1,
        ]);
    }

    /**
     * Record shipping revenue
     */
    public function recordShippingRevenue(Order $order): ?Accounting
    {
        if (!$order->shipping_cost || $order->shipping_cost <= 0) {
            return null;
        }

        return Accounting::create([
            'date' => $order->order_date,
            'type' => 'revenue',
            'category' => 'shipping',
            'amount' => $order->shipping_cost,
            'description' => "Shipping revenue from Order #{$order->order_number}",
            'reference_type' => 'order',
            'reference_id' => $order->id,
            'created_by' => $order->created_by ?? 1,
        ]);
    }

    /**
     * Record cost of goods sold
     */
    public function recordCostOfGoods(Order $order): array
    {
        $entries = [];

        // Material cost
        if ($order->material_cost && $order->material_cost > 0) {
            $entries[] = Accounting::create([
                'date' => $order->order_date,
                'type' => 'expense',
                'category' => 'material_cost',
                'amount' => $order->material_cost,
                'description' => "Material cost for Order #{$order->order_number}",
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'created_by' => $order->created_by ?? 1,
            ]);
        }

        // Labor cost
        if ($order->labor_cost && $order->labor_cost > 0) {
            $entries[] = Accounting::create([
                'date' => $order->order_date,
                'type' => 'expense',
                'category' => 'labor_cost',
                'amount' => $order->labor_cost,
                'description' => "Labor cost for Order #{$order->order_number}",
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'created_by' => $order->created_by ?? 1,
            ]);
        }

        // Overhead cost
        if ($order->overhead_cost && $order->overhead_cost > 0) {
            $entries[] = Accounting::create([
                'date' => $order->order_date,
                'type' => 'expense',
                'category' => 'overhead',
                'amount' => $order->overhead_cost,
                'description' => "Overhead cost for Order #{$order->order_number}",
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'created_by' => $order->created_by ?? 1,
            ]);
        }

        return $entries;
    }

    /**
     * Record profit entry
     */
    public function recordProfitEntry(Order $order): ?Accounting
    {
        $profit = $order->final_amount - ($order->total_cost ?? 0);

        if ($profit <= 0) {
            return null; // Don't record zero or negative profit
        }

        return Accounting::create([
            'date' => $order->order_date,
            'type' => 'revenue',
            'category' => 'profit',
            'amount' => $profit,
            'description' => "Profit from Order #{$order->order_number} (Margin: {$order->profit_margin}%)",
            'reference_type' => 'order',
            'reference_id' => $order->id,
            'created_by' => $order->created_by ?? 1,
        ]);
    }

    /**
     * Auto-process order accounting when completed
     */
    public function autoProcessOrder(Order $order): array
    {
        $entries = [];

        DB::transaction(function () use ($order, &$entries) {
            // Record revenue
            $entries['revenue'] = $this->recordOrderRevenue($order);

            // Record shipping revenue if applicable
            if ($order->shipping_cost) {
                $entries['shipping'] = $this->recordShippingRevenue($order);
            }

            // Record costs
            $entries['costs'] = $this->recordCostOfGoods($order);

            // Record profit
            $entries['profit'] = $this->recordProfitEntry($order);

            // Create journal entries (double-entry bookkeeping)
            $entries['journal'] = $this->createOrderJournalEntries($order);
        });

        return $entries;
    }

    /**
     * Create journal entries for order (double-entry bookkeeping)
     */
    protected function createOrderJournalEntries(Order $order): array
    {
        $entries = [];

        // Debit: Accounts Receivable / Cash | Credit: Sales Revenue
        $entries[] = JournalEntry::create([
            'entry_date' => $order->order_date,
            'description' => "Sales from Order #{$order->order_number}",
            'debit_account' => $order->payment_status === 'paid' ? 'Cash' : 'Accounts Receivable',
            'credit_account' => 'Sales Revenue',
            'amount' => $order->final_amount,
            'reference_type' => 'order',
            'reference_id' => $order->id,
            'created_by' => $order->created_by ?? 1,
        ]);

        // Debit: Cost of Goods Sold | Credit: Inventory
        if ($order->total_cost && $order->total_cost > 0) {
            $entries[] = JournalEntry::create([
                'entry_date' => $order->order_date,
                'description' => "COGS for Order #{$order->order_number}",
                'debit_account' => 'Cost of Goods Sold',
                'credit_account' => 'Inventory',
                'amount' => $order->total_cost,
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'created_by' => $order->created_by ?? 1,
            ]);
        }

        return $entries;
    }

    /**
     * Record payroll expense
     */
    public function recordPayrollExpense(Payroll $payroll): array
    {
        $entries = [];

        DB::transaction(function () use ($payroll, &$entries) {
            // Record payroll expense
            $entries['expense'] = Accounting::create([
                'date' => $payroll->payment_date ?? now(),
                'type' => 'expense',
                'category' => 'salary',
                'amount' => $payroll->total_amount,
                'description' => "Payroll for {$payroll->employee->user->name} - " . $payroll->period_start->format('M Y'),
                'reference_type' => 'payroll',
                'reference_id' => $payroll->id,
                'created_by' => $payroll->created_by,
            ]);

            // Create journal entry
            $entries['journal'] = JournalEntry::create([
                'entry_date' => $payroll->payment_date ?? now(),
                'description' => "Payroll payment - {$payroll->employee->user->name}",
                'debit_account' => 'Salary Expense',
                'credit_account' => 'Cash',
                'amount' => $payroll->total_amount,
                'reference_type' => 'payroll',
                'reference_id' => $payroll->id,
                'created_by' => $payroll->created_by,
            ]);
        });

        return $entries;
    }

    /**
     * Get accounting summary for a period
     */
    public function getAccountingSummary($startDate, $endDate): array
    {
        $revenue = Accounting::where('type', 'revenue')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $expenses = Accounting::where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $profit = $revenue - $expenses;

        return [
            'revenue' => round($revenue, 2),
            'expenses' => round($expenses, 2),
            'profit' => round($profit, 2),
            'profit_margin' => $revenue > 0 ? round(($profit / $revenue) * 100, 2) : 0,
        ];
    }

    /**
     * Get category breakdown
     */
    public function getCategoryBreakdown(string $type, $startDate, $endDate): array
    {
        return Accounting::where('type', $type)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category')
            ->toArray();
    }
}

