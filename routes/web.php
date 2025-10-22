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

// Material Routes
Route::resource('materials', MaterialController::class);
Route::post('materials/{material}/adjust-inventory', [MaterialController::class, 'adjustInventory'])->name('materials.adjust-inventory');

// Order Routes
Route::resource('orders', OrderController::class);
Route::post('orders/sync-woocommerce', [OrderController::class, 'syncFromWooCommerce'])->name('orders.sync-woocommerce');
Route::post('orders/{order}/recalculate-costs', [OrderController::class, 'recalculateCosts'])->name('orders.recalculate-costs');
Route::get('orders/{order}/cost-breakdown', [OrderController::class, 'costBreakdown'])->name('orders.cost-breakdown');

// Invoice Routes
Route::resource('invoices', InvoiceController::class);
Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');

// Purchase Order Routes
Route::resource('purchases', PurchaseController::class);
Route::post('purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');

// Production Order Routes
Route::resource('productions', ProductionController::class);
Route::post('production-stages/{stage}/start', [ProductionController::class, 'startStage'])->name('production-stages.start');
Route::post('production-stages/{stage}/complete', [ProductionController::class, 'completeStage'])->name('production-stages.complete');

// Warehouse Routes
Route::get('warehouses/low-stock', [WarehouseController::class, 'lowStock'])->name('warehouses.low-stock');
Route::get('warehouses/{warehouse}/inventory', [WarehouseController::class, 'inventory'])->name('warehouses.inventory');
Route::get('warehouses/{warehouse}/movements', [WarehouseController::class, 'movements'])->name('warehouses.movements');
Route::resource('warehouses', WarehouseController::class);

// Accounting Routes
Route::get('accounting/journal', [AccountingController::class, 'journalEntries'])->name('accounting.journal.index');
Route::get('accounting/journal/create', [AccountingController::class, 'createJournalEntry'])->name('accounting.journal.create');
Route::post('accounting/journal', [AccountingController::class, 'storeJournalEntry'])->name('accounting.journal.store');
Route::get('accounting/reports', [AccountingController::class, 'reports'])->name('accounting.reports');
Route::resource('accounting', AccountingController::class);

// User Routes
Route::resource('users', UserController::class);

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
