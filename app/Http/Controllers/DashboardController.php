<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionOrder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Material;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'in_production' => ProductionOrder::where('status', 'in-progress')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'low_stock_materials' => Material::whereHas('inventories', function($q) {
                $q->whereRaw('quantity <= reorder_level');
            })->count(),
        ];

        $recent_orders = Order::with('customer')->latest()->take(5)->get();
        $production_orders = ProductionOrder::with(['order', 'product'])->where('status', 'in-progress')->take(5)->get();

        return view('dashboard', compact('stats', 'recent_orders', 'production_orders'));
    }
}
