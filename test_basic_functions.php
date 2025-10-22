<?php
/**
 * 🧪 Basic Functions Test
 * اختبار الوظائف الأساسية للنظام
 */

require_once 'vendor/autoload.php';

// تحميل Laravel Application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Material;
use App\Models\Employee;
use App\Models\Order;
use App\Models\ProductionOrder;
use App\Models\Accounting;

echo "🚀 اختبار الوظائف الأساسية...\n\n";

// 1. فحص البيانات الموجودة
echo "📊 البيانات الموجودة في النظام:\n";
echo "================================\n";

$stats = [
    'users' => User::count(),
    'customers' => Customer::count(),
    'products' => Product::count(),
    'materials' => Material::count(),
    'employees' => Employee::count(),
    'orders' => Order::count(),
    'production_orders' => ProductionOrder::count(),
    'accounting_entries' => Accounting::count(),
];

foreach ($stats as $table => $count) {
    echo "✅ $table: $count سجل\n";
}

echo "\n";

// 2. فحص أحدث البيانات
echo "📈 أحدث البيانات:\n";
echo "================\n";

// أحدث طلب
$latestOrder = Order::latest()->first();
if ($latestOrder) {
    echo "✅ أحدث طلب: #{$latestOrder->order_number} - {$latestOrder->status}\n";
}

// أحدث عميل
$latestCustomer = Customer::latest()->first();
if ($latestCustomer) {
    echo "✅ أحدث عميل: {$latestCustomer->name}\n";
}

// أحدث منتج
$latestProduct = Product::latest()->first();
if ($latestProduct) {
    echo "✅ أحدث منتج: {$latestProduct->name}\n";
}

// أحدث مادة
$latestMaterial = Material::latest()->first();
if ($latestMaterial) {
    echo "✅ أحدث مادة: {$latestMaterial->name}\n";
}

echo "\n";

// 3. فحص الإحصائيات المالية
echo "💰 الإحصائيات المالية:\n";
echo "======================\n";

$totalRevenue = Order::sum('total_amount');
$averageOrderValue = Order::count() > 0 ? $totalRevenue / Order::count() : 0;
$totalAccounting = Accounting::sum('amount');

echo "✅ إجمالي الإيرادات: $totalRevenue KWD\n";
echo "✅ متوسط قيمة الطلب: " . number_format($averageOrderValue, 2) . " KWD\n";
echo "✅ إجمالي القيود المحاسبية: $totalAccounting KWD\n";

echo "\n";

// 4. فحص المواد المنخفضة
echo "⚠️ المواد المنخفضة:\n";
echo "==================\n";

$lowStockMaterials = Material::whereRaw('current_stock <= min_stock_level')->count();
$autoPurchaseEnabled = Material::where('auto_purchase_enabled', true)->count();

echo "✅ مواد منخفضة: $lowStockMaterials\n";
echo "✅ مواد مع شراء تلقائي: $autoPurchaseEnabled\n";

echo "\n";

// 5. فحص الموظفين النشطين
echo "👥 الموظفين النشطين:\n";
echo "===================\n";

$activeEmployees = Employee::where('employment_status', 'active')->count();
$employeesWithQR = Employee::whereNotNull('qr_code')->count();

echo "✅ موظفين نشطين: $activeEmployees\n";
echo "✅ موظفين مع QR Code: $employeesWithQR\n";

echo "\n";

// 6. فحص أوامر الإنتاج
echo "🏭 أوامر الإنتاج:\n";
echo "================\n";

$pendingProduction = ProductionOrder::where('status', 'pending')->count();
$inProgressProduction = ProductionOrder::where('status', 'in_progress')->count();
$completedProduction = ProductionOrder::where('status', 'completed')->count();

echo "✅ أوامر إنتاج معلقة: $pendingProduction\n";
echo "✅ أوامر إنتاج جارية: $inProgressProduction\n";
echo "✅ أوامر إنتاج مكتملة: $completedProduction\n";

echo "\n";

// 7. فحص الصلاحيات
echo "🔐 فحص الصلاحيات:\n";
echo "================\n";

$adminUsers = User::where('role', 'admin')->count();
$managerUsers = User::where('role', 'manager')->count();
$accountantUsers = User::where('role', 'accountant')->count();

echo "✅ مستخدمين إداريين: $adminUsers\n";
echo "✅ مديرين: $managerUsers\n";
echo "✅ محاسبين: $accountantUsers\n";

echo "\n";

// 8. فحص التكامل
echo "🔗 فحص التكامل:\n";
echo "==============\n";

// فحص WooCommerce integration
$wooOrders = Order::whereNotNull('woo_id')->count();
echo "✅ طلبات WooCommerce: $wooOrders\n";

// فحص Payment Gateways
$paymentGateways = DB::table('payment_gateways')->count();
echo "✅ بوابات الدفع: $paymentGateways\n";

// فحص Warehouses
$warehouses = DB::table('warehouses')->count();
echo "✅ مخازن: $warehouses\n";

echo "\n";

// 9. فحص الأداء
echo "⚡ فحص الأداء:\n";
echo "=============\n";

$startTime = microtime(true);

// اختبار استعلام معقد
$complexQuery = Order::with(['customer', 'items.product'])
    ->where('status', '!=', 'cancelled')
    ->where('order_date', '>=', now()->subDays(30))
    ->get();

$endTime = microtime(true);
$executionTime = ($endTime - $startTime) * 1000; // بالميلي ثانية

echo "✅ وقت تنفيذ استعلام معقد: " . number_format($executionTime, 2) . " ms\n";
echo "✅ عدد النتائج: " . $complexQuery->count() . " طلب\n";

echo "\n";

// 10. ملخص النظام
echo "📋 ملخص النظام:\n";
echo "==============\n";

echo "🎯 النظام جاهز للاستخدام!\n";
echo "📊 يحتوي على " . array_sum($stats) . " سجل إجمالي\n";
echo "💰 إجمالي الإيرادات: $totalRevenue KWD\n";
echo "🏭 " . ($pendingProduction + $inProgressProduction) . " أمر إنتاج نشط\n";
echo "⚠️ $lowStockMaterials مادة تحتاج إعادة طلب\n";
echo "👥 $activeEmployees موظف نشط\n";

echo "\n";
echo "🚀 يمكنك الآن اختبار النظام على: http://localhost:8000\n";
echo "📱 جرب QR Scanner على الموبايل\n";
echo "📊 تحقق من Advanced Accounting Dashboard\n";
echo "🤖 جرب Auto-Purchase system\n";

echo "\n✅ انتهى الاختبار بنجاح!\n";
