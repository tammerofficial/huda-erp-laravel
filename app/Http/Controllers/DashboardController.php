<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionOrder;
use App\Models\ProductionStage;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Material;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // Production-focused stats
            'in_production' => ProductionOrder::whereIn('status', ['pending', 'in-progress'])->count(),
            'completed_today' => ProductionOrder::where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count(),
            'active_employees' => Employee::where('employment_status', 'active')
                ->whereHas('productionStages', function($q) {
                    $q->where('status', 'in-progress');
                })
                ->count(),
            'pending_orders' => Order::where('status', 'on-hold')->count(),
            
            // Additional stats for Quick Stats section
            'stages_completed_today' => ProductionStage::where('status', 'completed')
                ->whereDate('end_time', today())
                ->count(),
            'completed_this_week' => ProductionOrder::where('status', 'completed')
                ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'total_materials' => Material::where('is_active', true)->count(),
            'low_stock_materials' => Material::where('is_active', true)
                ->where('auto_purchase_enabled', true)
                ->get()
                ->filter(function ($material) {
                    return $material->inventories()->sum('quantity') <= $material->min_stock_level;
                })
                ->count(),
            'total_employees' => Employee::where('employment_status', 'active')->count(),
            
            // Legacy stats (kept for compatibility)
            'total_revenue' => Order::sum('final_amount'),
        ];

        // Get recent orders
        $recent_orders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();
        
        // Get active production orders
        $production_orders = ProductionOrder::with(['order.customer', 'product'])
            ->whereIn('status', ['pending', 'in-progress'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_orders', 'production_orders'));
    }
}
