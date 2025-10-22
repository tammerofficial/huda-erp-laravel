<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\ProductionOrder;
use App\Models\BillOfMaterial;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Employee;
use App\Models\User;
use App\Models\Payroll;
use App\Models\WooCommerceSale;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountingDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Date filters
        $dateFrom = $request->input('date_from', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $period = $request->input('period', 'month'); // day, week, month, year

        // Orders Analytics
        $ordersData = $this->getOrdersAnalytics($dateFrom, $dateTo, $period);
        
        // Customers Analytics
        $customersData = $this->getCustomersAnalytics($dateFrom, $dateTo);
        
        // Products Analytics
        $productsData = $this->getProductsAnalytics($dateFrom, $dateTo);
        
        // Invoices Analytics
        $invoicesData = $this->getInvoicesAnalytics($dateFrom, $dateTo, $period);
        
        // Production Analytics
        $productionData = $this->getProductionAnalytics($dateFrom, $dateTo);
        
        // BOM Analytics
        $bomData = $this->getBOMAnalytics();
        
        // Materials & Inventory
        $materialsData = $this->getMaterialsAnalytics($dateFrom, $dateTo);
        
        // Warehouses Analytics
        $warehousesData = $this->getWarehousesAnalytics();
        
        // Purchasing Analytics
        $purchasingData = $this->getPurchasingAnalytics($dateFrom, $dateTo);
        
        // HR Analytics
        $hrData = $this->getHRAnalytics($dateFrom, $dateTo);
        
        // WooCommerce Integration
        $woocommerceData = $this->getWooCommerceAnalytics($dateFrom, $dateTo);
        
        // Payment Gateways
        $paymentData = $this->getPaymentAnalytics($dateFrom, $dateTo);
        
        // Overall Financial Summary
        $financialSummary = $this->getFinancialSummary($dateFrom, $dateTo);
        
        // Profitability Analysis
        $profitability = $this->getProfitabilityAnalysis($dateFrom, $dateTo);

        return view('accounting.advanced-dashboard', compact(
            'ordersData',
            'customersData',
            'productsData',
            'invoicesData',
            'productionData',
            'bomData',
            'materialsData',
            'warehousesData',
            'purchasingData',
            'hrData',
            'woocommerceData',
            'paymentData',
            'financialSummary',
            'profitability',
            'dateFrom',
            'dateTo',
            'period'
        ));
    }

    private function getOrdersAnalytics($dateFrom, $dateTo, $period)
    {
        // Get ERP orders
        $erpOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo]);
        $erpTotal = $erpOrders->sum('total_amount');
        $erpCount = $erpOrders->count();
        
        // Get WooCommerce orders
        $wooOrders = WooCommerceSale::whereBetween('order_date', [$dateFrom, $dateTo]);
        $wooTotal = $wooOrders->sum('total');
        $wooCount = $wooOrders->count();
        
        $totalOrders = $erpCount + $wooCount;
        $totalRevenue = $erpTotal + $wooTotal;
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'average_order_value' => $averageOrderValue,
            'erp_orders' => $erpCount,
            'woocommerce_orders' => $wooCount,
            'erp_revenue' => $erpTotal,
            'woocommerce_revenue' => $wooTotal,
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'by_status' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
                ->groupBy('status')
                ->get(),
            'timeline' => $this->getTimelineData(Order::class, $dateFrom, $dateTo, $period, 'total_amount'),
        ];
    }

    private function getCustomersAnalytics($dateFrom, $dateTo)
    {
        return [
            'total_customers' => Customer::count(),
            'new_customers' => Customer::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'active_customers' => Customer::whereHas('orders', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            })->count(),
            'top_customers' => Customer::withSum(['orders' => function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('created_at', [$dateFrom, $dateTo]);
                }], 'total_amount')
                ->orderByDesc('orders_sum_total_amount')
                ->limit(10)
                ->get(),
            'customer_distribution' => Customer::select('customer_type', DB::raw('count(*) as count'))
                ->groupBy('customer_type')
                ->get(),
        ];
    }

    private function getProductsAnalytics($dateFrom, $dateTo)
    {
        return [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
            'low_stock' => Product::whereColumn('stock_quantity', '<=', 'reorder_level')->count(),
            'top_selling' => DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
                ->select('products.id', 'products.name', 'products.sku', 
                    DB::raw('SUM(order_items.quantity) as total_quantity'),
                    DB::raw('SUM(order_items.total_price) as total_revenue'))
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->orderByDesc('total_quantity')
                ->limit(10)
                ->get(),
            'by_category' => Product::select('category', 
                DB::raw('count(*) as count'), 
                DB::raw('sum(stock_quantity) as total_stock'))
                ->groupBy('category')
                ->get(),
        ];
    }

    private function getInvoicesAnalytics($dateFrom, $dateTo, $period)
    {
        $query = Invoice::whereBetween('created_at', [$dateFrom, $dateTo]);
        
        return [
            'total_invoices' => $query->count(),
            'total_billed' => $query->sum('total_amount'),
            'total_paid' => Invoice::where('status', 'paid')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount'),
            'total_pending' => Invoice::where('status', 'pending')->sum('total_amount'),
            'total_overdue' => Invoice::where('status', 'overdue')->sum('total_amount'),
            'average_invoice' => $query->avg('total_amount'),
            'by_status' => Invoice::whereBetween('created_at', [$dateFrom, $dateTo])
                ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
                ->groupBy('status')
                ->get(),
            'timeline' => $this->getTimelineData(Invoice::class, $dateFrom, $dateTo, $period, 'total_amount'),
        ];
    }

    private function getProductionAnalytics($dateFrom, $dateTo)
    {
        return [
            'total_production_orders' => ProductionOrder::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'completed_productions' => ProductionOrder::where('status', 'completed')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'in_progress' => ProductionOrder::where('status', 'in-progress')->count(),
            'pending' => ProductionOrder::where('status', 'pending')->count(),
            'total_production_cost' => ProductionOrder::whereBetween('created_at', [$dateFrom, $dateTo])->sum('actual_cost'),
            'by_product' => DB::table('production_orders')
                ->join('products', 'production_orders.product_id', '=', 'products.id')
                ->whereBetween('production_orders.created_at', [$dateFrom, $dateTo])
                ->select('products.name', 
                    DB::raw('SUM(production_orders.quantity) as total_quantity'),
                    DB::raw('SUM(production_orders.actual_cost) as total_cost'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_quantity')
                ->limit(10)
                ->get(),
        ];
    }

    private function getBOMAnalytics()
    {
        return [
            'total_boms' => BillOfMaterial::count(),
            'active_boms' => BillOfMaterial::where('status', 'active')->count(),
            'products_with_bom' => BillOfMaterial::distinct('product_id')->count(),
            'average_materials_per_bom' => DB::table('bom_items')
                ->select(DB::raw('AVG(material_count) as avg'))
                ->from(DB::raw('(SELECT bom_id, COUNT(*) as material_count FROM bom_items GROUP BY bom_id) as subquery'))
                ->value('avg'),
        ];
    }

    private function getMaterialsAnalytics($dateFrom, $dateTo)
    {
        // Calculate total stock value from inventories
        $totalStockValue = DB::table('material_inventories')
            ->join('materials', 'material_inventories.material_id', '=', 'materials.id')
            ->select(DB::raw('SUM(material_inventories.quantity * materials.unit_cost) as total_value'))
            ->value('total_value') ?? 0;

        // Get materials with low stock - using subquery with proper GROUP BY
        $lowStockMaterials = DB::select("
            SELECT m.id
            FROM materials m
            LEFT JOIN material_inventories mi ON m.id = mi.material_id
            WHERE m.min_stock_level IS NOT NULL
            GROUP BY m.id, m.min_stock_level
            HAVING COALESCE(SUM(mi.quantity), 0) <= m.min_stock_level
        ");
        $lowStockCount = count($lowStockMaterials);

        // Get out of stock materials
        $outOfStock = DB::select("
            SELECT m.id
            FROM materials m
            LEFT JOIN material_inventories mi ON m.id = mi.material_id
            GROUP BY m.id
            HAVING COALESCE(SUM(mi.quantity), 0) = 0
        ");
        $outOfStockCount = count($outOfStock);

        return [
            'total_materials' => Material::count(),
            'total_stock_value' => $totalStockValue,
            'low_stock_materials' => $lowStockCount,
            'out_of_stock' => $outOfStockCount,
            'top_used' => DB::table('bom_items')
                ->join('materials', 'bom_items.material_id', '=', 'materials.id')
                ->leftJoin(
                    DB::raw('(SELECT material_id, SUM(quantity) as total_qty FROM material_inventories GROUP BY material_id) as mi'),
                    'materials.id', '=', 'mi.material_id'
                )
                ->select('materials.name', 'materials.sku',
                    DB::raw('SUM(bom_items.quantity) as total_required'),
                    DB::raw('COALESCE(mi.total_qty, 0) as current_stock'))
                ->groupBy('materials.id', 'materials.name', 'materials.sku', 'mi.total_qty')
                ->orderByDesc('total_required')
                ->limit(10)
                ->get(),
            'by_category' => DB::select("
                SELECT 
                    m.category,
                    COUNT(DISTINCT m.id) as count,
                    COALESCE(SUM(mi.quantity), 0) as total_stock,
                    COALESCE(SUM(mi.quantity * m.unit_cost), 0) as total_value
                FROM materials m
                LEFT JOIN material_inventories mi ON m.id = mi.material_id
                GROUP BY m.category
            "),
        ];
    }

    private function getWarehousesAnalytics()
    {
        // Calculate total stock from material_inventories
        $warehouseStats = DB::table('warehouses')
            ->leftJoin('material_inventories', 'warehouses.id', '=', 'material_inventories.warehouse_id')
            ->select(
                'warehouses.id',
                'warehouses.name',
                'warehouses.location',
                'warehouses.capacity',
                'warehouses.manager_id',
                DB::raw('COALESCE(SUM(material_inventories.quantity), 0) as current_stock')
            )
            ->groupBy('warehouses.id', 'warehouses.name', 'warehouses.location', 'warehouses.capacity', 'warehouses.manager_id')
            ->get();

        $totalCurrentStock = $warehouseStats->sum('current_stock');
        $totalCapacity = Warehouse::sum('capacity') ?? 0;
        $avgUtilization = $totalCapacity > 0 ? ($totalCurrentStock / $totalCapacity) * 100 : 0;

        return [
            'total_warehouses' => Warehouse::count(),
            'active_warehouses' => Warehouse::where('is_active', true)->count(),
            'total_capacity' => $totalCapacity,
            'total_current_stock' => $totalCurrentStock,
            'utilization_rate' => $avgUtilization,
            'by_warehouse' => Warehouse::with('manager')
                ->get()
                ->map(function($warehouse) {
                    $currentStock = DB::table('material_inventories')
                        ->where('warehouse_id', $warehouse->id)
                        ->sum('quantity') ?? 0;
                    
                    $warehouse->current_stock = $currentStock;
                    $warehouse->utilization = $warehouse->capacity > 0 
                        ? ($currentStock / $warehouse->capacity) * 100 
                        : 0;
                    return $warehouse;
                }),
        ];
    }

    private function getPurchasingAnalytics($dateFrom, $dateTo)
    {
        return [
            'total_purchase_orders' => PurchaseOrder::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_spent' => PurchaseOrder::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount'),
            'pending_orders' => PurchaseOrder::where('status', 'pending')->count(),
            'completed_orders' => PurchaseOrder::where('status', 'completed')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'top_suppliers' => Supplier::withSum(['purchaseOrders' => function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('created_at', [$dateFrom, $dateTo]);
                }], 'total_amount')
                ->orderByDesc('purchase_orders_sum_total_amount')
                ->limit(10)
                ->get(),
            'average_po_value' => PurchaseOrder::whereBetween('created_at', [$dateFrom, $dateTo])->avg('total_amount'),
        ];
    }

    private function getHRAnalytics($dateFrom, $dateTo)
    {
        return [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('employment_status', 'active')->count(),
            'total_users' => User::count(),
            'total_payroll' => Payroll::whereBetween('period_start', [$dateFrom, $dateTo])->sum('total_amount'),
            'paid_payroll' => Payroll::where('status', 'paid')->whereBetween('period_start', [$dateFrom, $dateTo])->sum('total_amount'),
            'pending_payroll' => Payroll::where('status', 'draft')->sum('total_amount'),
            'by_department' => Employee::select('department', DB::raw('count(*) as count'), DB::raw('sum(salary) as total_salary'))
                ->where('employment_status', 'active')
                ->groupBy('department')
                ->get(),
            'by_position' => Employee::select('position', DB::raw('count(*) as count'))
                ->where('employment_status', 'active')
                ->groupBy('position')
                ->get(),
        ];
    }

    private function getWooCommerceAnalytics($dateFrom, $dateTo)
    {
        $wooCount = WooCommerceSale::count();
        
        if ($wooCount == 0) {
            return [
                'total_sales' => 0,
                'total_orders' => 0,
                'total_profit' => 0,
                'average_order' => 0,
                'by_status' => collect([]),
                'by_payment_method' => collect([]),
            ];
        }

        return [
            'total_sales' => WooCommerceSale::whereBetween('order_date', [$dateFrom, $dateTo])->sum('total') ?? 0,
            'total_orders' => WooCommerceSale::whereBetween('order_date', [$dateFrom, $dateTo])->count(),
            'total_profit' => WooCommerceSale::whereBetween('order_date', [$dateFrom, $dateTo])->sum('profit') ?? 0,
            'average_order' => WooCommerceSale::whereBetween('order_date', [$dateFrom, $dateTo])->avg('total') ?? 0,
            'by_status' => WooCommerceSale::whereBetween('order_date', [$dateFrom, $dateTo])
                ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
                ->groupBy('status')
                ->get(),
            'by_payment_method' => WooCommerceSale::whereBetween('order_date', [$dateFrom, $dateTo])
                ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
                ->groupBy('payment_method')
                ->get(),
        ];
    }

    private function getPaymentAnalytics($dateFrom, $dateTo)
    {
        $paymentCount = PaymentTransaction::count();
        
        if ($paymentCount == 0) {
            return [
                'total_transactions' => 0,
                'total_amount' => 0,
                'total_fees' => 0,
                'net_amount' => 0,
                'by_gateway' => collect([]),
            ];
        }

        return [
            'total_transactions' => PaymentTransaction::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_amount' => PaymentTransaction::where('status', 'completed')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('amount') ?? 0,
            'total_fees' => PaymentTransaction::where('status', 'completed')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('fee') ?? 0,
            'net_amount' => PaymentTransaction::where('status', 'completed')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('net_amount') ?? 0,
            'by_gateway' => PaymentTransaction::join('payment_gateways', 'payment_transactions.payment_gateway_id', '=', 'payment_gateways.id')
                ->whereBetween('payment_transactions.created_at', [$dateFrom, $dateTo])
                ->where('payment_transactions.status', 'completed')
                ->select('payment_gateways.name',
                    DB::raw('count(*) as count'),
                    DB::raw('sum(payment_transactions.amount) as total'),
                    DB::raw('sum(payment_transactions.fee) as fees'))
                ->groupBy('payment_gateways.id', 'payment_gateways.name')
                ->get(),
        ];
    }

    private function getFinancialSummary($dateFrom, $dateTo)
    {
        // ERP Revenue
        $erpRevenue = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('total_amount') ?? 0;
        
        // WooCommerce Revenue
        $wooRevenue = WooCommerceSale::whereBetween('order_date', [$dateFrom, $dateTo])
            ->sum('total') ?? 0;
        
        $totalRevenue = $erpRevenue + $wooRevenue;
        
        // Production Costs
        $productionCosts = ProductionOrder::whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('actual_cost') ?? 0;
        
        // Purchase Costs
        $purchaseCosts = PurchaseOrder::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('total_amount') ?? 0;
        
        // Payroll Costs
        $payrollCosts = Payroll::where('status', 'paid')
            ->whereBetween('period_start', [$dateFrom, $dateTo])
            ->sum('total_amount') ?? 0;
        
        $totalCosts = $productionCosts + $purchaseCosts + $payrollCosts;
        
        $netProfit = $totalRevenue - $totalCosts;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
        
        return [
            'total_revenue' => $totalRevenue,
            'erp_revenue' => $erpRevenue,
            'woocommerce_revenue' => $wooRevenue,
            'production_costs' => $productionCosts,
            'purchase_costs' => $purchaseCosts,
            'payroll_costs' => $payrollCosts,
            'total_costs' => $totalCosts,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
        ];
    }

    private function getProfitabilityAnalysis($dateFrom, $dateTo)
    {
        // Calculate profitability by product
        $productProfitability = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('production_orders', 'production_orders.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->select('products.name',
                DB::raw('SUM(order_items.total_price) as revenue'),
                DB::raw('SUM(COALESCE(production_orders.actual_cost, 0)) as cost'),
                DB::raw('SUM(order_items.total_price) - SUM(COALESCE(production_orders.actual_cost, 0)) as profit'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('profit')
            ->limit(10)
            ->get();

        return [
            'by_product' => $productProfitability,
        ];
    }

    private function getTimelineData($model, $dateFrom, $dateTo, $period, $sumColumn = null)
    {
        $format = match($period) {
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m',
        };

        $query = $model::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(DB::raw("DATE_FORMAT(created_at, '{$format}') as period"))
            ->groupBy('period')
            ->orderBy('period');

        if ($sumColumn) {
            $query->addSelect(DB::raw("SUM({$sumColumn}) as total"));
        } else {
            $query->addSelect(DB::raw("COUNT(*) as total"));
        }

        return $query->get();
    }
}

