<?php

namespace App\Services;

use App\Models\Material;
use App\Models\ProductionOrder;
use App\Models\User;
use App\Models\Payroll;
use App\Notifications\LowStockAlert;
use App\Notifications\PayrollDueAlert;
use App\Notifications\QualityCheckRequired;
use App\Notifications\ProductionDelayAlert;
use Carbon\Carbon;

class AlertService
{
    /**
     * Check for low stock materials
     */
    public function checkMaterialShortage()
    {
        $lowStockMaterials = Material::whereRaw(
            '(SELECT SUM(quantity) FROM material_inventories WHERE material_id = materials.id) <= reorder_level'
        )->get();

        foreach ($lowStockMaterials as $material) {
            // Notify managers
            User::role('manager')->each(function($user) use ($material) {
                $user->notify(new LowStockAlert($material));
            });
        }

        return $lowStockMaterials->count();
    }

    /**
     * Check for production delays
     */
    public function checkProductionDelay()
    {
        $delayedOrders = ProductionOrder::where('status', 'in_production')
            ->where('expected_completion_date', '<', now())
            ->get();

        foreach ($delayedOrders as $order) {
            // Notify managers
            User::role('manager')->each(function($user) use ($order) {
                $user->notify(new ProductionDelayAlert($order));
            });
        }

        return $delayedOrders->count();
    }

    /**
     * Check for pending quality checks
     */
    public function checkQualityPending()
    {
        $pendingOrders = ProductionOrder::where('status', 'awaiting_quality_check')
            ->where('created_at', '<', now()->subHours(24))
            ->get();

        foreach ($pendingOrders as $order) {
            // Notify quality inspectors
            User::role('quality_inspector')->each(function($user) use ($order) {
                $user->notify(new QualityCheckRequired($order));
            });
        }

        return $pendingOrders->count();
    }

    /**
     * Check for payroll due dates
     */
    public function checkPayrollDue()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Check if payroll is due (25th of each month)
        if (now()->day >= 25) {
            $unpaidPayrolls = Payroll::where('status', 'approved')
                ->whereMonth('period_start', $currentMonth)
                ->whereYear('period_start', $currentYear)
                ->whereNull('payment_date')
                ->get();

            foreach ($unpaidPayrolls as $payroll) {
                // Notify HR and managers
                User::role(['hr_manager', 'manager'])->each(function($user) use ($payroll) {
                    $user->notify(new PayrollDueAlert($payroll));
                });
            }

            return $unpaidPayrolls->count();
        }

        return 0;
    }

    /**
     * Run all alert checks
     */
    public function runAllChecks()
    {
        $results = [
            'low_stock' => $this->checkMaterialShortage(),
            'production_delays' => $this->checkProductionDelay(),
            'quality_pending' => $this->checkQualityPending(),
            'payroll_due' => $this->checkPayrollDue(),
        ];

        return $results;
    }

    /**
     * Get dashboard alerts
     */
    public function getDashboardAlerts()
    {
        $alerts = [];

        // Low stock alerts
        $lowStockCount = Material::whereRaw(
            '(SELECT SUM(quantity) FROM material_inventories WHERE material_id = materials.id) <= reorder_level'
        )->count();

        if ($lowStockCount > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$lowStockCount} مواد بمخزون منخفض",
                'url' => route('materials.low-stock'),
                'icon' => 'exclamation-triangle'
            ];
        }

        // Production delays
        $delayedCount = ProductionOrder::where('status', 'in_production')
            ->where('expected_completion_date', '<', now())
            ->count();

        if ($delayedCount > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$delayedCount} طلب إنتاج متأخر",
                'url' => route('productions.index'),
                'icon' => 'clock'
            ];
        }

        // Pending quality checks
        $pendingQualityCount = ProductionOrder::where('status', 'awaiting_quality_check')
            ->where('created_at', '<', now()->subHours(24))
            ->count();

        if ($pendingQualityCount > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$pendingQualityCount} فحص جودة في الانتظار",
                'url' => route('quality-checks.index'),
                'icon' => 'check-circle'
            ];
        }

        return $alerts;
    }
}
