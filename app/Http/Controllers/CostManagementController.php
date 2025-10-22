<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\BillOfMaterial;
use App\Models\Material;
use App\Models\ProductionOrder;
use App\Services\ProductCostCalculator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CostManagementController extends Controller
{
    protected $costCalculator;

    public function __construct(ProductCostCalculator $costCalculator)
    {
        $this->costCalculator = $costCalculator;
    }

    /**
     * Display the cost management dashboard
     */
    public function dashboard()
    {
        $dateFrom = request('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', Carbon::now()->format('Y-m-d'));

        // Cost Overview Statistics
        $stats = [
            'total_products' => Product::count(),
            'products_with_costs' => Product::whereNotNull('cost')->count(),
            'total_orders' => Order::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'orders_with_costs' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNotNull('total_cost')->count(),
            'total_materials' => Material::count(),
            'active_boms' => BillOfMaterial::where('status', 'active')->count(),
        ];

        // Cost Analysis Data
        $costAnalysis = [
            'average_product_cost' => Product::whereNotNull('cost')->avg('cost') ?? 0,
            'average_order_cost' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNotNull('total_cost')->avg('total_cost') ?? 0,
            'total_material_value' => Material::sum(DB::raw('unit_cost * (SELECT COALESCE(SUM(quantity), 0) FROM material_inventories WHERE material_id = materials.id)')) ?? 0,
            'production_costs' => ProductionOrder::whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('actual_cost') ?? 0,
        ];

        // Recent Cost Updates
        $recentUpdates = Product::whereNotNull('cost')
            ->where('updated_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Cost Trends (Last 6 Months)
        $costTrends = Product::select(
                DB::raw('DATE_FORMAT(updated_at, "%Y-%m") as month'),
                DB::raw('AVG(cost) as avg_cost'),
                DB::raw('COUNT(*) as products_updated')
            )
            ->whereNotNull('cost')
            ->where('updated_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('cost-management.dashboard', compact(
            'stats', 'costAnalysis', 'recentUpdates', 'costTrends', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Display product costs management
     */
    public function products(Request $request)
    {
        $query = Product::with(['billOfMaterials']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('cost_range')) {
            switch ($request->cost_range) {
                case 'no_cost':
                    $query->whereNull('cost');
                    break;
                case 'low_cost':
                    $query->where('cost', '<', 50);
                    break;
                case 'medium_cost':
                    $query->whereBetween('cost', [50, 200]);
                    break;
                case 'high_cost':
                    $query->where('cost', '>', 200);
                    break;
            }
        }

        if ($request->filled('has_bom')) {
            if ($request->has_bom === 'yes') {
                $query->whereHas('billOfMaterials');
            } else {
                $query->whereDoesntHave('billOfMaterials');
            }
        }

        $products = $query->paginate(20);

        // Statistics
        $stats = [
            'total_products' => Product::count(),
            'with_costs' => Product::whereNotNull('cost')->count(),
            'without_costs' => Product::whereNull('cost')->count(),
            'with_bom' => Product::whereHas('billOfMaterials')->count(),
            'average_cost' => Product::whereNotNull('cost')->avg('cost') ?? 0,
        ];

        return view('cost-management.products', compact('products', 'stats'));
    }

    /**
     * Display order costs management
     */
    public function orders(Request $request)
    {
        $query = Order::with(['customer', 'orderItems.product']);

        // Apply filters
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('has_costs')) {
            if ($request->has_costs === 'yes') {
                $query->whereNotNull('total_cost');
            } else {
                $query->whereNull('total_cost');
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total_orders' => Order::count(),
            'with_costs' => Order::whereNotNull('total_cost')->count(),
            'without_costs' => Order::whereNull('total_cost')->count(),
            'total_revenue' => Order::sum('total_amount') ?? 0,
            'total_costs' => Order::whereNotNull('total_cost')->sum('total_cost') ?? 0,
            'average_margin' => Order::whereNotNull('total_cost')
                ->selectRaw('AVG((total_amount - total_cost) / total_amount * 100) as margin')
                ->value('margin') ?? 0,
        ];

        return view('cost-management.orders', compact('orders', 'stats'));
    }

    /**
     * Display profitability analysis
     */
    public function profitability(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        // Overall Profitability
        $profitability = [
            'total_revenue' => Order::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount') ?? 0,
            'total_costs' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNotNull('total_cost')->sum('total_cost') ?? 0,
            'gross_profit' => 0,
            'profit_margin' => 0,
        ];

        if ($profitability['total_revenue'] > 0) {
            $profitability['gross_profit'] = $profitability['total_revenue'] - $profitability['total_costs'];
            $profitability['profit_margin'] = ($profitability['gross_profit'] / $profitability['total_revenue']) * 100;
        }

        // Top Profitable Products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->whereNotNull('orders.total_cost')
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total_price) as total_revenue'),
                DB::raw('AVG(products.cost) as avg_cost'),
                DB::raw('SUM(order_items.quantity * products.cost) as total_cost'),
                DB::raw('SUM(order_items.total_price) - SUM(order_items.quantity * products.cost) as profit')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('profit')
            ->limit(10)
            ->get();

        // Profitability by Month
        $monthlyProfitability = Order::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(COALESCE(total_cost, 0)) as costs'),
                DB::raw('SUM(total_amount) - SUM(COALESCE(total_cost, 0)) as profit')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Cost Breakdown
        $costBreakdown = [
            'material_costs' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNotNull('material_cost')->sum('material_cost') ?? 0,
            'labor_costs' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNotNull('labor_cost')->sum('labor_cost') ?? 0,
            'overhead_costs' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNotNull('overhead_cost')->sum('overhead_cost') ?? 0,
            'shipping_costs' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNotNull('shipping_cost')->sum('shipping_cost') ?? 0,
        ];

        return view('cost-management.profitability', compact(
            'profitability', 'topProducts', 'monthlyProfitability', 'costBreakdown', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Recalculate costs for a specific product
     */
    public function recalculateProductCost(Product $product)
    {
        try {
            $newCost = $this->costCalculator->calculateProductCost($product);
            $product->update(['cost' => $newCost]);
            
            return response()->json([
                'success' => true,
                'new_cost' => $newCost,
                'message' => 'Product cost recalculated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to recalculate cost: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recalculate costs for a specific order
     */
    public function recalculateOrderCost(Order $order)
    {
        try {
            $costs = $this->costCalculator->calculateOrderCosts($order);
            $order->update($costs);
            
            return response()->json([
                'success' => true,
                'costs' => $costs,
                'message' => 'Order costs recalculated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to recalculate costs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk recalculate costs for all products
     */
    public function bulkRecalculateProducts()
    {
        try {
            $products = Product::whereHas('billOfMaterials')->get();
            $updated = 0;
            $errors = [];

            foreach ($products as $product) {
                try {
                    $newCost = $this->costCalculator->calculateProductCost($product);
                    $product->update(['cost' => $newCost]);
                    $updated++;
                } catch (\Exception $e) {
                    $errors[] = "Product {$product->name}: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'updated' => $updated,
                'errors' => $errors,
                'message' => "Updated {$updated} products successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk recalculation failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
