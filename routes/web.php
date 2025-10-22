<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BillOfMaterialController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AccountingDashboardController;
use App\Http\Controllers\CostManagementController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Authentication Routes
Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// Employee Routes
Route::resource('employees', EmployeeController::class);
Route::post('employees/{employee}/generate-qr', [EmployeeController::class, 'generateQR'])->name('employees.generate-qr');

// Customer Routes
Route::resource('customers', CustomerController::class);

// Supplier Routes
Route::resource('suppliers', SupplierController::class);

// Product Routes
Route::resource('products', ProductController::class);
Route::get('products/{product}/bom/create', [ProductController::class, 'createBOM'])->name('products.bom.create');
Route::post('products/{product}/bom', [ProductController::class, 'storeBOM'])->name('products.bom.store');
Route::post('products/{product}/calculate-cost', [ProductController::class, 'calculateCost'])->name('products.calculate-cost');
Route::post('products/{product}/update-pricing', [ProductController::class, 'updatePricing'])->name('products.update-pricing');

// Bill of Materials (BOM) Routes - Independent Management
Route::resource('bom', BillOfMaterialController::class)->parameters(['bom' => 'billOfMaterial']);
Route::post('bom/{billOfMaterial}/duplicate', [BillOfMaterialController::class, 'duplicate'])->name('bom.duplicate');
Route::post('bom/{billOfMaterial}/activate', [BillOfMaterialController::class, 'activate'])->name('bom.activate');
Route::post('bom/bulk-create', [BillOfMaterialController::class, 'bulkCreate'])->name('bom.bulk-create');

// Material Routes
Route::resource('materials', MaterialController::class);
Route::get('materials/{material}/adjust-inventory', [MaterialController::class, 'showAdjustInventoryForm'])->name('materials.adjust-inventory.form');
Route::post('materials/{material}/adjust-inventory', [MaterialController::class, 'adjustInventory'])->name('materials.adjust-inventory');
Route::get('materials-low-stock', [MaterialController::class, 'lowStock'])->name('materials.low-stock');

// Order Routes
Route::resource('orders', OrderController::class);
Route::patch('orders/{order}/update-priority', [OrderController::class, 'updatePriority'])->name('orders.update-priority');
Route::post('orders/sync-woocommerce', [OrderController::class, 'syncFromWooCommerce'])->name('orders.sync-woocommerce');
Route::post('orders/{order}/recalculate-costs', [OrderController::class, 'recalculateCosts'])->name('orders.recalculate-costs');
Route::get('orders/{order}/cost-breakdown', [OrderController::class, 'costBreakdown'])->name('orders.cost-breakdown');

// Invoice Routes
Route::resource('invoices', InvoiceController::class);
Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');

// Purchase Order Routes
Route::resource('purchases', PurchaseController::class)->parameters(['purchases' => 'purchaseOrder']);
Route::post('purchases/{purchaseOrder}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');

// Production Order Routes
Route::get('productions/dashboard', [ProductionController::class, 'dashboard'])->name('productions.dashboard');
Route::get('productions/dashboard-data', [ProductionController::class, 'getDashboardData'])->name('productions.dashboard-data');
Route::resource('productions', ProductionController::class);
Route::get('productions/order/{order}/details', [ProductionController::class, 'getOrderDetails'])->name('productions.order-details');
Route::post('production-stages/{stage}/assign', [ProductionController::class, 'assignStage'])->name('production-stages.assign');
Route::post('production-stages/{stage}/start', [ProductionController::class, 'startStage'])->name('production-stages.start');
Route::post('production-stages/{stage}/complete', [ProductionController::class, 'completeStage'])->name('production-stages.complete');

// Warehouse Routes
Route::get('warehouses/low-stock', [WarehouseController::class, 'lowStock'])->name('warehouses.low-stock');
Route::get('warehouses/{warehouse}/inventory', [WarehouseController::class, 'inventory'])->name('warehouses.inventory');
Route::get('warehouses/{warehouse}/movements', [WarehouseController::class, 'movements'])->name('warehouses.movements');
Route::resource('warehouses', WarehouseController::class);

// Accounting Routes
Route::get('accounting/advanced-dashboard', [AccountingDashboardController::class, 'index'])->name('accounting.advanced-dashboard');
Route::get('accounting/journal', [AccountingController::class, 'journalEntries'])->name('accounting.journal.index');
Route::get('accounting/journal/create', [AccountingController::class, 'createJournalEntry'])->name('accounting.journal.create');
Route::post('accounting/journal', [AccountingController::class, 'storeJournalEntry'])->name('accounting.journal.store');
Route::get('accounting/reports', [AccountingController::class, 'reports'])->name('accounting.reports');
Route::resource('accounting', AccountingController::class);

// Payroll Routes
Route::get('payroll/generate', [PayrollController::class, 'showGenerateForm'])->name('payroll.generate');
Route::post('payroll/generate', [PayrollController::class, 'generateMonthly'])->name('payroll.generate.store');
Route::post('payroll/{payroll}/approve', [PayrollController::class, 'approve'])->name('payroll.approve');
Route::post('payroll/{payroll}/mark-paid', [PayrollController::class, 'markAsPaid'])->name('payroll.mark-paid');
Route::resource('payroll', PayrollController::class);

// Cost Management Routes
Route::prefix('cost-management')->name('cost-management.')->group(function () {
    Route::get('dashboard', [CostManagementController::class, 'dashboard'])->name('dashboard');
    Route::get('products', [CostManagementController::class, 'products'])->name('products');
    Route::get('orders', [CostManagementController::class, 'orders'])->name('orders');
    Route::get('profitability', [CostManagementController::class, 'profitability'])->name('profitability');
    
    // AJAX Routes
    Route::post('products/{product}/recalculate', [CostManagementController::class, 'recalculateProductCost'])->name('products.recalculate');
    Route::post('orders/{order}/recalculate', [CostManagementController::class, 'recalculateOrderCost'])->name('orders.recalculate');
    Route::post('products/bulk-recalculate', [CostManagementController::class, 'bulkRecalculateProducts'])->name('products.bulk-recalculate');
});

// WooCommerce Integration
Route::post('woocommerce/sync', [OrderController::class, 'syncFromWooCommerce'])->name('woocommerce.sync');

// User Routes
Route::resource('users', UserController::class);
Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

// Reports Routes
Route::get('reports/sales', function () {
    return view('reports.sales');
})->name('reports.sales');

Route::get('reports/inventory', function () {
    return view('reports.inventory');
})->name('reports.inventory');

Route::get('reports/production', function () {
    return view('reports.production');
})->name('reports.production');

Route::get('reports/profitability', function () {
    $startDate = request('start_date', now()->startOfMonth());
    $endDate = request('end_date', now()->endOfMonth());
    
    $accountingService = app(\App\Services\AccountingService::class);
    $summary = $accountingService->getAccountingSummary($startDate, $endDate);
    
    $profitableOrders = \App\Models\Order::profitable()
        ->whereBetween('order_date', [$startDate, $endDate])
        ->with('customer')
        ->orderBy('profit_margin', 'desc')
        ->get();
    
    return view('reports.profitability', compact('summary', 'profitableOrders', 'startDate', 'endDate'));
})->name('reports.profitability');

// Settings Routes
Route::get('settings', [SettingsController::class, 'index'])->name('settings');
Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
