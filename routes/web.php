<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProductionLogController;
use App\Http\Controllers\QualityCheckController;
use App\Http\Controllers\QRController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Authentication Routes
Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('login.post');

Route::post('logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// Employee Routes
Route::middleware(['permission:employees.view'])->group(function () {
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{employee}/generate-qr', [EmployeeController::class, 'generateQR'])->name('employees.generate-qr');
    Route::post('employees/generate-all-qr', [EmployeeController::class, 'generateAllQR'])->name('employees.generate-all-qr');
    Route::post('employees/{employee}/toggle-qr', [EmployeeController::class, 'toggleQR'])->name('employees.toggle-qr');
});

// Customer Routes
Route::middleware(['permission:customers.view'])->group(function () {
    Route::resource('customers', CustomerController::class);
});

// Supplier Routes
Route::middleware(['permission:suppliers.view'])->group(function () {
    Route::resource('suppliers', SupplierController::class);
});

// Product Routes
Route::middleware(['permission:products.view'])->group(function () {
    Route::resource('products', ProductController::class);
});
Route::middleware(['permission:products.edit'])->group(function () {
    Route::get('products/{product}/bom/create', [ProductController::class, 'createBOM'])->name('products.bom.create');
    Route::post('products/{product}/bom', [ProductController::class, 'storeBOM'])->name('products.bom.store');
    Route::post('products/{product}/calculate-cost', [ProductController::class, 'calculateCost'])->name('products.calculate-cost');
    Route::post('products/{product}/update-pricing', [ProductController::class, 'updatePricing'])->name('products.update-pricing');
});

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

// User Routes (Management team only)
Route::middleware(['permission:users.view'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

// Role Management Routes (Management team only)
Route::prefix('roles')->name('roles.')->middleware(['permission:roles.view'])->group(function () {
    Route::get('/', [RoleManagementController::class, 'index'])->name('index');
    Route::get('create', [RoleManagementController::class, 'create'])->name('create');
    Route::post('/', [RoleManagementController::class, 'store'])->name('store');
    Route::get('{user}', [RoleManagementController::class, 'show'])->name('show');
    Route::get('{user}/edit', [RoleManagementController::class, 'edit'])->name('edit');
    Route::put('{user}', [RoleManagementController::class, 'update'])->name('update');
    Route::delete('{user}', [RoleManagementController::class, 'destroy'])->name('destroy');
    Route::post('{user}/toggle-status', [RoleManagementController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('{user}/reset-password', [RoleManagementController::class, 'resetPassword'])->name('reset-password');
    Route::post('bulk-action', [RoleManagementController::class, 'bulkAction'])->name('bulk-action');
});

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

// Attendance Routes
Route::resource('attendance', AttendanceController::class);
Route::post('attendance/bulk-check-in', [AttendanceController::class, 'bulkCheckIn'])->name('attendance.bulk-check-in');
Route::post('attendance/bulk-check-out', [AttendanceController::class, 'bulkCheckOut'])->name('attendance.bulk-check-out');
Route::get('attendance/report/{month}', [AttendanceController::class, 'monthlyReport'])->name('attendance.monthly-report');

// Production Logs Routes
Route::resource('production-logs', ProductionLogController::class);
Route::post('production-logs/{log}/complete', [ProductionLogController::class, 'complete'])->name('production-logs.complete');
Route::post('production-logs/{log}/approve', [ProductionLogController::class, 'approve'])->name('production-logs.approve');
Route::post('production-logs/{log}/reject', [ProductionLogController::class, 'reject'])->name('production-logs.reject');

// Quality Check Routes
Route::resource('quality-checks', QualityCheckController::class);
Route::get('quality-checks/inspect/{productionOrder}', [QualityCheckController::class, 'inspect'])->name('quality-checks.inspect');
Route::post('quality-checks/inspect/{productionOrder}', [QualityCheckController::class, 'submitInspection'])->name('quality-checks.submit-inspection');

// QR Code Routes
Route::post('api/qr/validate', [QRController::class, 'validate'])->name('qr.validate');
Route::post('api/qr/check-in', [QRController::class, 'checkIn'])->name('qr.check-in');
Route::post('api/qr/check-out', [QRController::class, 'checkOut'])->name('qr.check-out');
Route::post('api/qr/start-stage', [QRController::class, 'startStage'])->name('qr.start-stage');
Route::post('api/qr/complete-stage', [QRController::class, 'completeStage'])->name('qr.complete-stage');
Route::get('qr-scanner', function () {
    return view('qr-scanner');
})->name('qr.scanner');

// PDF/Excel Export Routes
Route::get('exports/invoice/{invoice}/pdf', function($invoice) {
    $service = app(\App\Services\PDFExportService::class);
    return $service->generateInvoice($invoice);
})->name('exports.invoice.pdf');

Route::get('exports/payslip/{payroll}/pdf', function($payroll) {
    $service = app(\App\Services\PDFExportService::class);
    return $service->generatePayslip($payroll);
})->name('exports.payslip.pdf');

Route::get('exports/attendance/{month}/{year}/pdf', function($month, $year) {
    $service = app(\App\Services\PDFExportService::class);
    return $service->generateAttendanceReport($month, $year);
})->name('exports.attendance.pdf');

Route::get('exports/attendance/{month}/{year}/excel', function($month, $year) {
    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\AttendanceExport($month, $year),
        'attendance-' . $month . '-' . $year . '.xlsx'
    );
})->name('exports.attendance.excel');

Route::get('exports/payroll/{month}/{year}/excel', function($month, $year) {
    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\PayrollExport($month, $year),
        'payroll-' . $month . '-' . $year . '.xlsx'
    );
})->name('exports.payroll.excel');

// Settings Routes
Route::get('settings', [SettingsController::class, 'index'])->name('settings');
Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
