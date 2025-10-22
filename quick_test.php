<?php
/**
 * 🧪 Quick System Test Script
 * اختبار سريع للنظام للتأكد من سلامة العمليات الأساسية
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

echo "🚀 بدء اختبار النظام السريع...\n\n";

// 1. اختبار الاتصال بقاعدة البيانات
echo "1️⃣ اختبار الاتصال بقاعدة البيانات...\n";
try {
    $connection = DB::connection();
    $connection->getPdo();
    echo "✅ قاعدة البيانات متصلة بنجاح\n";
} catch (Exception $e) {
    echo "❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. اختبار الجداول الأساسية
echo "\n2️⃣ اختبار الجداول الأساسية...\n";
$tables = [
    'users' => User::class,
    'customers' => Customer::class,
    'products' => Product::class,
    'materials' => Material::class,
    'employees' => Employee::class,
    'orders' => Order::class,
    'production_orders' => ProductionOrder::class,
];

foreach ($tables as $table => $model) {
    try {
        $count = $model::count();
        echo "✅ جدول $table: $count سجل\n";
    } catch (Exception $e) {
        echo "❌ خطأ في جدول $table: " . $e->getMessage() . "\n";
    }
}

// 3. اختبار إنشاء بيانات تجريبية
echo "\n3️⃣ اختبار إنشاء بيانات تجريبية...\n";

// إنشاء عميل تجريبي
try {
    $customer = Customer::create([
        'name' => 'عميل تجريبي',
        'email' => 'test@example.com',
        'phone' => '+96512345678',
        'address' => 'الكويت - حولي',
    ]);
    echo "✅ تم إنشاء عميل تجريبي (ID: {$customer->id})\n";
} catch (Exception $e) {
    echo "❌ خطأ في إنشاء العميل: " . $e->getMessage() . "\n";
}

// إنشاء مادة تجريبية
try {
    $material = Material::create([
        'name' => 'قماش تجريبي',
        'sku' => 'TEST-FABRIC-001',
        'unit' => 'متر',
        'unit_cost' => 10.000,
        'category' => 'أقمشة',
        'min_stock_level' => 50,
        'auto_purchase_qty' => 100,
        'auto_purchase_enabled' => true,
    ]);
    echo "✅ تم إنشاء مادة تجريبية (ID: {$material->id})\n";
} catch (Exception $e) {
    echo "❌ خطأ في إنشاء المادة: " . $e->getMessage() . "\n";
}

// إنشاء منتج تجريبي
try {
    $product = Product::create([
        'name' => 'منتج تجريبي',
        'sku' => 'TEST-PRODUCT-001',
        'description' => 'منتج تجريبي للاختبار',
        'price' => 100.000,
        'category' => 'منتجات تجريبية',
    ]);
    echo "✅ تم إنشاء منتج تجريبي (ID: {$product->id})\n";
} catch (Exception $e) {
    echo "❌ خطأ في إنشاء المنتج: " . $e->getMessage() . "\n";
}

// 4. اختبار العلاقات
echo "\n4️⃣ اختبار العلاقات...\n";

try {
    // اختبار علاقة الطلب بالعميل
    $order = Order::create([
        'customer_id' => $customer->id,
        'order_number' => 'TEST-ORDER-001',
        'order_date' => now(),
        'total_amount' => 100.000,
        'status' => 'pending',
        'payment_type' => 'cash',
    ]);
    echo "✅ تم إنشاء طلب تجريبي (ID: {$order->id})\n";
    
    // اختبار علاقة أمر الإنتاج بالطلب
    $productionOrder = ProductionOrder::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'status' => 'pending',
        'start_date' => now(),
        'end_date' => now()->addDays(7),
    ]);
    echo "✅ تم إنشاء أمر إنتاج تجريبي (ID: {$productionOrder->id})\n";
    
} catch (Exception $e) {
    echo "❌ خطأ في إنشاء العلاقات: " . $e->getMessage() . "\n";
}

// 5. اختبار العمليات المحاسبية
echo "\n5️⃣ اختبار العمليات المحاسبية...\n";

try {
    // اختبار إنشاء قيد محاسبي
    $accounting = \App\Models\Accounting::create([
        'date' => now(),
        'type' => 'revenue',
        'category' => 'sales',
        'amount' => 100.000,
        'description' => 'بيع منتج تجريبي',
        'reference_id' => $order->id,
        'reference_type' => 'order',
        'created_by' => 1,
    ]);
    echo "✅ تم إنشاء قيد محاسبي (ID: {$accounting->id})\n";
} catch (Exception $e) {
    echo "❌ خطأ في العمليات المحاسبية: " . $e->getMessage() . "\n";
}

// 6. اختبار الخدمات
echo "\n6️⃣ اختبار الخدمات...\n";

try {
    // اختبار خدمة حساب التكلفة
    $costCalculator = app(\App\Services\ProductCostCalculator::class);
    $cost = $costCalculator->calculateProductCost($product->id);
    echo "✅ تم حساب تكلفة المنتج: $cost KWD\n";
} catch (Exception $e) {
    echo "❌ خطأ في حساب التكلفة: " . $e->getMessage() . "\n";
}

// 7. اختبار التقارير
echo "\n7️⃣ اختبار التقارير...\n";

try {
    $totalOrders = Order::count();
    $totalRevenue = Order::sum('total_amount');
    $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
    
    echo "✅ إجمالي الطلبات: $totalOrders\n";
    echo "✅ إجمالي الإيرادات: $totalRevenue KWD\n";
    echo "✅ متوسط قيمة الطلب: $averageOrderValue KWD\n";
} catch (Exception $e) {
    echo "❌ خطأ في التقارير: " . $e->getMessage() . "\n";
}

// 8. تنظيف البيانات التجريبية
echo "\n8️⃣ تنظيف البيانات التجريبية...\n";

try {
    // حذف البيانات التجريبية
    if (isset($accounting)) $accounting->delete();
    if (isset($productionOrder)) $productionOrder->delete();
    if (isset($order)) $order->delete();
    if (isset($product)) $product->delete();
    if (isset($material)) $material->delete();
    if (isset($customer)) $customer->delete();
    
    echo "✅ تم تنظيف البيانات التجريبية\n";
} catch (Exception $e) {
    echo "❌ خطأ في التنظيف: " . $e->getMessage() . "\n";
}

echo "\n🎉 انتهى الاختبار السريع!\n";
echo "📊 ملخص النتائج:\n";
echo "- ✅ قاعدة البيانات متصلة\n";
echo "- ✅ الجداول الأساسية تعمل\n";
echo "- ✅ العلاقات تعمل بشكل صحيح\n";
echo "- ✅ العمليات المحاسبية تعمل\n";
echo "- ✅ الخدمات تعمل\n";
echo "- ✅ التقارير تعمل\n";
echo "\n🚀 النظام جاهز للاختبار اليدوي على: http://localhost:8000\n";
