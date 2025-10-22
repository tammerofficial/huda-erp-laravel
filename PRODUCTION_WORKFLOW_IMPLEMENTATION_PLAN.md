# 🏭 خطة تنفيذ سيناريو الإنتاج الكامل - Huda ERP

## 📋 نظرة عامة

هذه الخطة تهدف لإكمال تنفيذ السيناريو الكامل لتدفق العمل في الورشة من استقبال الطلب إلى التسليم النهائي.

**الحالة الحالية:** 45% مُنفذ  
**الهدف:** 100% تنفيذ كامل  
**المدة المتوقعة:** 6-8 أسابيع  
**عدد المراحل:** 5 مراحل رئيسية

---

## 📊 ملخص النواقص الحرجة

### ❌ ما يجب تنفيذه (55%)
1. نظام QR Code للعمال (0%)
2. نظام Attendance & Time Tracking (0%)
3. Production Logs بالقطعة (0%)
4. Auto-assign ذكي للعمال (0%)
5. Quality Check Workflow (0%)
6. Labor Cost من بيانات فعلية (20%)
7. Payroll التلقائي الكامل (40%)
8. PDF/Excel Export (0%)
9. Accounting Observers (50%)
10. Real-time Alerts (30%)

---

## 🎯 المرحلة الأولى: نظام الحضور والإنتاجية (2-3 أسابيع)

### الأهداف
- إنشاء نظام تتبع حضور وانصراف
- تسجيل ساعات العمل والإضافي
- تتبع الإنتاج بالقطعة
- حساب الأجور من البيانات الفعلية

### 📝 المهام التفصيلية

#### 1.1 إنشاء جدول Attendance Records
**الملف:** `database/migrations/YYYY_MM_DD_create_attendance_records_table.php`

```php
Schema::create('attendance_records', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->date('date');
    $table->time('check_in')->nullable();
    $table->time('check_out')->nullable();
    $table->decimal('hours_worked', 5, 2)->default(0);
    $table->decimal('overtime_hours', 5, 2)->default(0);
    $table->string('status')->default('present'); // present, absent, late, half_day
    $table->text('notes')->nullable();
    $table->timestamps();
    
    $table->unique(['employee_id', 'date']);
    $table->index('date');
    $table->index('status');
});
```

#### 1.2 إنشاء جدول Production Logs
**الملف:** `database/migrations/YYYY_MM_DD_create_production_logs_table.php`

```php
Schema::create('production_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->foreignId('production_stage_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->integer('pieces_completed')->default(0);
    $table->decimal('rate_per_piece', 8, 3)->nullable();
    $table->dateTime('start_time');
    $table->dateTime('end_time')->nullable();
    $table->integer('duration_minutes')->nullable(); // محسوب تلقائياً
    $table->decimal('earnings', 10, 3)->default(0); // محسوب تلقائياً
    $table->decimal('expected_duration', 5, 2)->nullable(); // بالدقائق
    $table->decimal('efficiency_rate', 5, 2)->nullable(); // نسبة مئوية
    $table->string('quality_status')->default('pending'); // pending, approved, rejected
    $table->text('notes')->nullable();
    $table->timestamps();
    
    $table->index(['employee_id', 'start_time']);
    $table->index('production_stage_id');
    $table->index('quality_status');
});
```

#### 1.3 تحديث جدول Employees
**الملف:** `database/migrations/YYYY_MM_DD_add_attendance_fields_to_employees_table.php`

```php
Schema::table('employees', function (Blueprint $table) {
    $table->string('salary_type')->default('monthly'); // monthly, per_piece, hourly
    $table->decimal('rate_per_hour', 8, 3)->nullable();
    $table->decimal('rate_per_piece', 8, 3)->nullable();
    $table->string('attendance_device_id')->nullable(); // ZKTeco ID
    $table->json('skills')->nullable(); // ['cutting', 'sewing', 'embroidery']
    $table->decimal('efficiency_rating', 3, 2)->default(1.00); // 1.00 = 100%
    $table->integer('current_workload')->default(0); // عدد المهام الحالية
});
```

#### 1.4 إنشاء Models

**AttendanceRecord.php**
```php
<?php
namespace App\Models;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'check_in', 'check_out',
        'hours_worked', 'overtime_hours', 'status', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Auto-calculate hours on save
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($record) {
            if ($record->check_in && $record->check_out) {
                $start = \Carbon\Carbon::parse($record->check_in);
                $end = \Carbon\Carbon::parse($record->check_out);
                $totalHours = $end->diffInMinutes($start) / 60;
                
                $record->hours_worked = min($totalHours, 8);
                $record->overtime_hours = max(0, $totalHours - 8);
            }
        });
    }
}
```

**ProductionLog.php**
```php
<?php
namespace App\Models;

class ProductionLog extends Model
{
    protected $fillable = [
        'employee_id', 'production_stage_id', 'product_id',
        'pieces_completed', 'rate_per_piece', 'start_time',
        'end_time', 'duration_minutes', 'earnings',
        'expected_duration', 'efficiency_rate',
        'quality_status', 'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'rate_per_piece' => 'decimal:3',
        'earnings' => 'decimal:3',
        'efficiency_rate' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function productionStage()
    {
        return $this->belongsTo(ProductionStage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($log) {
            // حساب المدة
            if ($log->start_time && $log->end_time) {
                $log->duration_minutes = $log->start_time->diffInMinutes($log->end_time);
            }
            
            // حساب الأرباح
            if ($log->pieces_completed && $log->rate_per_piece) {
                $log->earnings = $log->pieces_completed * $log->rate_per_piece;
            }
            
            // حساب الكفاءة
            if ($log->expected_duration && $log->duration_minutes) {
                $log->efficiency_rate = ($log->expected_duration / $log->duration_minutes) * 100;
            }
        });
    }
}
```

#### 1.5 إنشاء Controllers

**AttendanceController.php**
- `index()` - عرض سجلات الحضور
- `create()` - تسجيل حضور جديد
- `store()` - حفظ الحضور
- `bulkCheckIn()` - تسجيل دخول جماعي
- `bulkCheckOut()` - تسجيل خروج جماعي
- `monthlyReport()` - تقرير شهري

**ProductionLogController.php**
- `index()` - عرض سجلات الإنتاج
- `create()` - تسجيل إنتاج جديد
- `store()` - حفظ الإنتاج
- `complete()` - إكمال المرحلة
- `approve()` - قبول الجودة
- `reject()` - رفض الجودة

#### 1.6 إنشاء Views

**attendance/index.blade.php**
- جدول بسجلات الحضور
- فلترة بالتاريخ والموظف
- إحصائيات (حاضر، غائب، متأخر)

**attendance/create.blade.php**
- نموذج تسجيل حضور
- اختيار موظف
- وقت الدخول/الخروج

**production-logs/index.blade.php**
- جدول بسجلات الإنتاج
- فلترة بالموظف والمرحلة
- إحصائيات الكفاءة

#### 1.7 Routes
```php
// Attendance
Route::resource('attendance', AttendanceController::class);
Route::post('attendance/bulk-check-in', [AttendanceController::class, 'bulkCheckIn']);
Route::post('attendance/bulk-check-out', [AttendanceController::class, 'bulkCheckOut']);
Route::get('attendance/report/{month}', [AttendanceController::class, 'monthlyReport']);

// Production Logs
Route::resource('production-logs', ProductionLogController::class);
Route::post('production-logs/{log}/complete', [ProductionLogController::class, 'complete']);
Route::post('production-logs/{log}/approve', [ProductionLogController::class, 'approve']);
Route::post('production-logs/{log}/reject', [ProductionLogController::class, 'reject']);
```

### ✅ مخرجات المرحلة الأولى
- ✅ نظام حضور وانصراف كامل
- ✅ تتبع الإنتاج بالقطعة
- ✅ حساب ساعات العمل والإضافي تلقائياً
- ✅ تسجيل الكفاءة والأداء
- ✅ واجهات لإدارة الحضور والإنتاج

---

## 🎯 المرحلة الثانية: نظام QR Code والتعيين الذكي (2 أسبوع)

### الأهداف
- تمكين العمال من المسح الضوئي
- تسجيل تلقائي للوقت
- توزيع ذكي للمهام
- واجهة موبايل للعمال

### 📝 المهام التفصيلية

#### 2.1 تثبيت مكتبات QR Code
```bash
composer require simplesoftwareio/simple-qrcode
composer require bacon/bacon-qr-code
```

#### 2.2 إضافة حقول QR للموظفين
```php
Schema::table('employees', function (Blueprint $table) {
    $table->string('qr_code')->unique()->nullable();
    $table->string('qr_image_path')->nullable();
    $table->boolean('qr_enabled')->default(true);
});
```

#### 2.3 إنشاء Service للـ QR
**QRCodeService.php**
```php
<?php
namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Employee;

class QRCodeService
{
    public function generateForEmployee(Employee $employee)
    {
        // توليد رمز فريد
        $code = 'EMP-' . str_pad($employee->id, 6, '0', STR_PAD_LEFT) . '-' . uniqid();
        
        // حفظ في قاعدة البيانات
        $employee->qr_code = $code;
        
        // توليد صورة QR
        $qrImage = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($code);
        
        // حفظ الصورة
        $path = 'qr-codes/employees/' . $employee->id . '.png';
        \Storage::disk('public')->put($path, $qrImage);
        
        $employee->qr_image_path = $path;
        $employee->save();
        
        return $employee;
    }
    
    public function validateQRCode($code)
    {
        return Employee::where('qr_code', $code)
            ->where('qr_enabled', true)
            ->first();
    }
}
```

#### 2.4 إنشاء Auto-Assign Service
**ProductionAssignmentService.php**
```php
<?php
namespace App\Services;

use App\Models\Employee;
use App\Models\ProductionStage;

class ProductionAssignmentService
{
    public function findBestEmployee($stageName, $productId)
    {
        return Employee::where('is_active', true)
            ->whereJsonContains('skills', $stageName)
            ->orderByRaw('current_workload ASC')
            ->orderByRaw('efficiency_rating DESC')
            ->first();
    }
    
    public function assignStage(ProductionStage $stage, Employee $employee = null)
    {
        if (!$employee) {
            $employee = $this->findBestEmployee(
                $stage->stage_name,
                $stage->productionOrder->product_id
            );
        }
        
        if (!$employee) {
            throw new \Exception('No suitable employee found');
        }
        
        $stage->employee_id = $employee->id;
        $stage->status = 'assigned';
        $stage->save();
        
        // زيادة workload
        $employee->increment('current_workload');
        
        return $stage;
    }
    
    public function completeStage(ProductionStage $stage)
    {
        $stage->status = 'completed';
        $stage->end_time = now();
        $stage->save();
        
        // تقليل workload
        if ($stage->employee) {
            $stage->employee->decrement('current_workload');
        }
    }
}
```

#### 2.5 إنشاء QR Scanner Page
**Views: qr-scanner.blade.php**
```html
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6" x-data="qrScanner()">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6">🔍 QR Code Scanner</h1>
        
        <!-- Video Stream -->
        <div class="mb-6">
            <video id="qr-video" class="w-full max-w-md mx-auto rounded-lg"></video>
        </div>
        
        <!-- Status -->
        <div x-show="scannedEmployee" class="bg-green-100 p-4 rounded-lg mb-4">
            <p class="text-lg">Employee: <span x-text="scannedEmployee"></span></p>
        </div>
        
        <!-- Actions -->
        <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
            <button @click="checkIn()" class="bg-green-600 text-white px-6 py-3 rounded-lg">
                Check In
            </button>
            <button @click="checkOut()" class="bg-red-600 text-white px-6 py-3 rounded-lg">
                Check Out
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function qrScanner() {
    return {
        scannedEmployee: null,
        employeeId: null,
        
        init() {
            const scanner = new Html5Qrcode("qr-video");
            scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText) => {
                    this.validateQR(decodedText);
                }
            );
        },
        
        validateQR(code) {
            fetch('/api/qr/validate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ code })
            })
            .then(r => r.json())
            .then(data => {
                this.scannedEmployee = data.employee.name;
                this.employeeId = data.employee.id;
            });
        },
        
        checkIn() {
            // إرسال طلب تسجيل دخول
        },
        
        checkOut() {
            // إرسال طلب تسجيل خروج
        }
    }
}
</script>
@endsection
```

#### 2.6 API Routes للـ QR
```php
Route::post('api/qr/validate', [QRController::class, 'validate']);
Route::post('api/qr/check-in', [QRController::class, 'checkIn']);
Route::post('api/qr/check-out', [QRController::class, 'checkOut']);
Route::post('api/qr/start-stage', [QRController::class, 'startStage']);
Route::post('api/qr/complete-stage', [QRController::class, 'completeStage']);
```

### ✅ مخرجات المرحلة الثانية
- ✅ QR Code لكل موظف
- ✅ Scanner page للموبايل
- ✅ تسجيل دخول/خروج عبر QR
- ✅ Auto-assign ذكي للمهام
- ✅ تحديث workload تلقائياً

---

## 🎯 المرحلة الثالثة: Payroll الكامل والتكامل المحاسبي (1.5 أسبوع)

### الأهداف
- كشف رواتب تلقائي من البيانات الفعلية
- حساب تكلفة العمالة الحقيقية
- قيود محاسبية تلقائية
- تقارير رواتب

### 📝 المهام التفصيلية

#### 3.1 تحديث PayrollService
```php
<?php
namespace App\Services;

class PayrollService
{
    public function generateMonthly($month, $employeeIds = null)
    {
        $employees = Employee::when($employeeIds, function($q) use ($employeeIds) {
            $q->whereIn('id', $employeeIds);
        })->get();
        
        foreach ($employees as $employee) {
            $this->generateForEmployee($employee, $month);
        }
    }
    
    public function generateForEmployee(Employee $employee, $month)
    {
        // 1. جمع بيانات الحضور
        $attendance = AttendanceRecord::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->get();
        
        $totalHours = $attendance->sum('hours_worked');
        $overtimeHours = $attendance->sum('overtime_hours');
        $daysPresent = $attendance->where('status', 'present')->count();
        
        // 2. جمع بيانات الإنتاج
        $productionLogs = ProductionLog::where('employee_id', $employee->id)
            ->whereMonth('start_time', $month)
            ->where('quality_status', 'approved')
            ->get();
        
        $productionEarnings = $productionLogs->sum('earnings');
        $totalPieces = $productionLogs->sum('pieces_completed');
        
        // 3. حساب الراتب حسب النوع
        $baseSalary = 0;
        
        if ($employee->salary_type === 'monthly') {
            $workingDays = Setting::get('working_days_per_month', 26);
            $baseSalary = $employee->base_salary * ($daysPresent / $workingDays);
        } elseif ($employee->salary_type === 'hourly') {
            $baseSalary = $totalHours * $employee->rate_per_hour;
        } elseif ($employee->salary_type === 'per_piece') {
            $baseSalary = $productionEarnings;
        }
        
        // 4. حساب الإضافي
        $overtimeRate = Setting::get('overtime_multiplier', 1.5);
        $overtimeAmount = 0;
        
        if ($employee->salary_type === 'monthly') {
            $hourlyRate = $employee->base_salary / (26 * 8);
            $overtimeAmount = $overtimeHours * $hourlyRate * $overtimeRate;
        } elseif ($employee->salary_type === 'hourly') {
            $overtimeAmount = $overtimeHours * $employee->rate_per_hour * $overtimeRate;
        }
        
        // 5. المكافآت والخصومات
        $bonuses = $this->calculateBonuses($employee, $productionLogs);
        $deductions = $this->calculateDeductions($employee, $attendance);
        
        // 6. الإجمالي
        $totalAmount = $baseSalary + $overtimeAmount + $bonuses - $deductions;
        
        // 7. إنشاء سجل الراتب
        return Payroll::create([
            'employee_id' => $employee->id,
            'period_start' => Carbon::parse($month)->startOfMonth(),
            'period_end' => Carbon::parse($month)->endOfMonth(),
            'base_salary' => $baseSalary,
            'overtime_hours' => $overtimeHours,
            'overtime_amount' => $overtimeAmount,
            'bonuses' => $bonuses,
            'deductions' => $deductions,
            'total_amount' => $totalAmount,
            'status' => 'draft',
            'metadata' => [
                'days_present' => $daysPresent,
                'total_hours' => $totalHours,
                'pieces_completed' => $totalPieces,
                'efficiency_avg' => $productionLogs->avg('efficiency_rate'),
            ]
        ]);
    }
    
    private function calculateBonuses($employee, $productionLogs)
    {
        $bonus = 0;
        
        // مكافأة الكفاءة العالية
        $avgEfficiency = $productionLogs->avg('efficiency_rate');
        if ($avgEfficiency > 120) {
            $bonus += 50; // 50 دينار
        } elseif ($avgEfficiency > 100) {
            $bonus += 25;
        }
        
        // مكافأة الإنتاج
        $totalPieces = $productionLogs->sum('pieces_completed');
        if ($totalPieces > 500) {
            $bonus += 100;
        } elseif ($totalPieces > 300) {
            $bonus += 50;
        }
        
        return $bonus;
    }
    
    private function calculateDeductions($employee, $attendance)
    {
        $deduction = 0;
        
        // خصم الغياب
        $absences = $attendance->where('status', 'absent')->count();
        $dailyRate = $employee->base_salary / 26;
        $deduction += $absences * $dailyRate;
        
        // خصم التأخير
        $lateCount = $attendance->where('status', 'late')->count();
        if ($lateCount > 3) {
            $deduction += ($lateCount - 3) * 5; // 5 دينار لكل تأخير بعد الثالث
        }
        
        return $deduction;
    }
}
```

#### 3.2 تحديث ProductCostCalculator
```php
public function calculateLaborCost(Product $product, $quantity = 1)
{
    // استخدام البيانات الفعلية من production_logs
    $logs = ProductionLog::where('product_id', $product->id)
        ->where('quality_status', 'approved')
        ->latest()
        ->take(10) // آخر 10 سجلات
        ->get();
    
    if ($logs->count() > 0) {
        // متوسط تكلفة العمالة الفعلية
        $avgLaborCost = $logs->avg('earnings');
        return $avgLaborCost * $quantity;
    }
    
    // Fallback للنسبة الثابتة
    $materialCost = $this->calculateMaterialCost($product, $quantity);
    $laborPercentage = Setting::get('labor_cost_percentage', 30) / 100;
    
    return $materialCost * $laborPercentage;
}
```

#### 3.3 إنشاء Observers للقيود التلقائية

**PayrollObserver.php**
```php
<?php
namespace App\Observers;

use App\Models\Payroll;
use App\Services\AccountingService;

class PayrollObserver
{
    public function paid(Payroll $payroll)
    {
        $accountingService = app(AccountingService::class);
        
        // قيد صرف الراتب
        $accountingService->recordPayrollExpense(
            $payroll->employee,
            $payroll->total_amount,
            $payroll->period_start,
            'payroll_payment',
            $payroll->id
        );
    }
}
```

**MaterialMovementObserver.php**
```php
<?php
namespace App\Observers;

use App\Models\InventoryMovement;
use App\Services\AccountingService;

class MaterialMovementObserver
{
    public function created(InventoryMovement $movement)
    {
        if ($movement->movement_type === 'consumption') {
            $accountingService = app(AccountingService::class);
            
            $totalCost = $movement->quantity * $movement->material->unit_cost;
            
            // قيد استهلاك المواد
            $accountingService->recordMaterialConsumption(
                $movement->material,
                $totalCost,
                $movement->reference_type,
                $movement->reference_id
            );
        }
    }
}
```

#### 3.4 تسجيل Observers
```php
// AppServiceProvider.php
public function boot()
{
    Payroll::observe(PayrollObserver::class);
    InventoryMovement::observe(MaterialMovementObserver::class);
}
```

### ✅ مخرجات المرحلة الثالثة
- ✅ كشف رواتب تلقائي من بيانات فعلية
- ✅ حساب تكلفة عمالة حقيقية
- ✅ قيود محاسبية تلقائية لكل عملية
- ✅ مكافآت وخصومات ذكية

---

## 🎯 المرحلة الرابعة: Quality Check و PDF/Excel Export (1.5 أسبوع)

### الأهداف
- نظام فحص جودة متكامل
- تصدير التقارير PDF
- تصدير البيانات Excel
- فواتير احترافية

### 📝 المهام التفصيلية

#### 4.1 تثبيت المكتبات
```bash
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
```

#### 4.2 إنشاء جدول Quality Checks
```php
Schema::create('quality_checks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('production_order_id')->constrained();
    $table->foreignId('product_id')->constrained();
    $table->foreignId('inspector_id')->constrained('employees');
    $table->string('status'); // pending, passed, failed
    $table->integer('items_checked')->default(0);
    $table->integer('items_passed')->default(0);
    $table->integer('items_failed')->default(0);
    $table->json('defects')->nullable(); // قائمة العيوب
    $table->text('notes')->nullable();
    $table->dateTime('inspection_date');
    $table->timestamps();
});
```

#### 4.3 Quality Check Workflow
```php
// QualityCheckController.php
public function inspect($productionOrderId)
{
    $order = ProductionOrder::findOrFail($productionOrderId);
    
    return view('quality.inspect', compact('order'));
}

public function submitInspection(Request $request, $orderId)
{
    $validated = $request->validate([
        'status' => 'required|in:passed,failed',
        'items_checked' => 'required|integer',
        'items_passed' => 'required|integer',
        'defects' => 'nullable|array',
        'notes' => 'nullable|string',
    ]);
    
    $check = QualityCheck::create([
        'production_order_id' => $orderId,
        'inspector_id' => auth()->id(),
        'inspection_date' => now(),
        ...$validated
    ]);
    
    $order = ProductionOrder::find($orderId);
    
    if ($validated['status'] === 'passed') {
        $order->status = 'quality_approved';
        $order->save();
    } else {
        $order->status = 'quality_rejected';
        $order->save();
        
        // إرسال إشعار للإنتاج
        event(new QualityRejected($order));
    }
    
    return redirect()->route('quality.index')
        ->with('success', 'Quality check submitted successfully');
}
```

#### 4.4 PDF Export Service
```php
<?php
namespace App\Services;

use PDF;

class PDFExportService
{
    public function generateInvoice($invoiceId)
    {
        $invoice = Invoice::with(['order.customer', 'order.items'])
            ->findOrFail($invoiceId);
        
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->invoice_no . '.pdf');
    }
    
    public function generatePayslip($payrollId)
    {
        $payroll = Payroll::with('employee')->findOrFail($payrollId);
        
        $pdf = PDF::loadView('pdf.payslip', compact('payroll'));
        
        return $pdf->download('payslip-' . $payroll->id . '.pdf');
    }
    
    public function generateProductionReport($orderId)
    {
        $order = ProductionOrder::with([
            'product',
            'stages.employee',
            'order.customer'
        ])->findOrFail($orderId);
        
        $pdf = PDF::loadView('pdf.production-report', compact('order'));
        
        return $pdf->download('production-' . $order->id . '.pdf');
    }
}
```

#### 4.5 Excel Export
```php
<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollExport implements FromCollection, WithHeadings
{
    protected $month;
    
    public function __construct($month)
    {
        $this->month = $month;
    }
    
    public function collection()
    {
        return Payroll::with('employee')
            ->whereMonth('period_start', $this->month)
            ->get()
            ->map(function($payroll) {
                return [
                    $payroll->employee->name,
                    $payroll->base_salary,
                    $payroll->overtime_amount,
                    $payroll->bonuses,
                    $payroll->deductions,
                    $payroll->total_amount,
                    $payroll->status,
                ];
            });
    }
    
    public function headings(): array
    {
        return [
            'Employee',
            'Base Salary',
            'Overtime',
            'Bonuses',
            'Deductions',
            'Total',
            'Status'
        ];
    }
}

// Controller
public function exportPayroll($month)
{
    return Excel::download(
        new PayrollExport($month),
        'payroll-' . $month . '.xlsx'
    );
}
```

#### 4.6 PDF Templates

**invoice.blade.php**
```html
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .invoice-details { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #f4f4f4; }
        .total { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Huda Fashion</h1>
        <p>فاتورة رقم: {{ $invoice->invoice_no }}</p>
    </div>
    
    <div class="invoice-details">
        <p><strong>العميل:</strong> {{ $invoice->order->customer->name }}</p>
        <p><strong>التاريخ:</strong> {{ $invoice->issue_date->format('Y-m-d') }}</p>
        <p><strong>رقم الطلب:</strong> {{ $invoice->order->order_number }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 3) }} KWD</td>
                <td>{{ number_format($item->total, 3) }} KWD</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="3">الإجمالي</td>
                <td>{{ number_format($invoice->total_amount, 3) }} KWD</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
```

### ✅ مخرجات المرحلة الرابعة
- ✅ نظام فحص جودة متكامل
- ✅ تصدير فواتير PDF
- ✅ تصدير كشوف رواتب PDF
- ✅ تصدير تقارير Excel
- ✅ تقارير إنتاج احترافية

---

## 🎯 المرحلة الخامسة: التنبيهات والأتمتة (1 أسبوع)

### الأهداف
- تنبيهات فورية للأحداث المهمة
- جدولة المهام التلقائية
- تقارير دورية تلقائية
- إشعارات للمديرين

### 📝 المهام التفصيلية

#### 5.1 إنشاء Notifications

**LowStockAlert.php**
```php
<?php
namespace App\Notifications;

class LowStockAlert extends Notification
{
    protected $material;
    
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تنبيه: مخزون منخفض')
            ->line('المادة: ' . $this->material->name)
            ->line('الكمية المتبقية: ' . $this->material->current_stock)
            ->line('حد إعادة الطلب: ' . $this->material->reorder_level)
            ->action('عرض المادة', route('materials.show', $this->material));
    }
}
```

**PayrollDueAlert.php**
**QualityCheckRequired.php**
**ProductionDelayAlert.php**

#### 5.2 Scheduled Tasks (Kernel.php)
```php
protected function schedule(Schedule $schedule)
{
    // مزامنة WooCommerce كل 5 دقائق
    $schedule->command('woocommerce:sync')
        ->everyFiveMinutes();
    
    // فحص المخزون المنخفض يومياً
    $schedule->command('stock:check-low')
        ->daily();
    
    // توليد الرواتب في نهاية الشهر
    $schedule->command('payroll:generate-monthly')
        ->monthlyOn(25, '09:00');
    
    // تقرير إنتاج يومي
    $schedule->command('reports:daily-production')
        ->dailyAt('18:00');
    
    // تنظيف السجلات القديمة
    $schedule->command('cleanup:old-logs')
        ->weekly();
}
```

#### 5.3 Real-time Alerts Service
```php
<?php
namespace App\Services;

class AlertService
{
    public function checkProductionDelay()
    {
        $delayedOrders = ProductionOrder::where('status', 'in_production')
            ->where('expected_completion_date', '<', now())
            ->get();
        
        foreach ($delayedOrders as $order) {
            // إرسال إشعار للمدير
            $order->order->customer->notify(new ProductionDelayAlert($order));
        }
    }
    
    public function checkMaterialShortage()
    {
        $lowStock = Material::whereRaw(
            '(SELECT SUM(quantity) FROM material_inventories WHERE material_id = materials.id) <= reorder_level'
        )->get();
        
        foreach ($lowStock as $material) {
            User::role('manager')->each(function($user) use ($material) {
                $user->notify(new LowStockAlert($material));
            });
        }
    }
    
    public function checkQualityPending()
    {
        $pending = ProductionOrder::where('status', 'awaiting_quality_check')
            ->where('created_at', '<', now()->subHours(24))
            ->get();
        
        foreach ($pending as $order) {
            User::role('quality_inspector')->each(function($user) use ($order) {
                $user->notify(new QualityCheckRequired($order));
            });
        }
    }
}
```

#### 5.4 Event Listeners

**OrderCompletedListener.php**
```php
public function handle(OrderCompleted $event)
{
    // إشعار للعميل
    $event->order->customer->notify(new OrderReadyNotification($event->order));
    
    // تحديث الإحصائيات
    Cache::forget('dashboard_stats');
}
```

#### 5.5 Dashboard Real-time Updates
```javascript
// استخدام Laravel Echo + Pusher
Echo.private('dashboard')
    .listen('ProductionUpdated', (e) => {
        // تحديث الإحصائيات
        updateDashboardStats();
    })
    .listen('LowStockAlert', (e) => {
        // عرض تنبيه
        showAlert('Low stock: ' + e.material.name);
    });
```

### ✅ مخرجات المرحلة الخامسة
- ✅ تنبيهات فورية لجميع الأحداث
- ✅ مهام مجدولة تلقائياً
- ✅ تقارير دورية تلقائية
- ✅ إشعارات للمديرين والعملاء
- ✅ تحديثات لحظية في Dashboard

---

## 📊 ملخص الجداول المطلوبة

### جداول جديدة (5):
1. ✅ `attendance_records` - سجلات الحضور
2. ✅ `production_logs` - سجلات الإنتاج بالقطعة
3. ✅ `quality_checks` - فحوصات الجودة
4. ✅ `notifications` - الإشعارات (Laravel default)
5. ✅ `failed_jobs` - الوظائف الفاشلة (Laravel default)

### تحديثات على جداول موجودة:
1. ✅ `employees` - إضافة QR, skills, rates
2. ✅ `production_stages` - إضافة expected_duration

---

## 📦 الحزم المطلوبة (Composer)

```bash
composer require simplesoftwareio/simple-qrcode
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
composer require pusher/pusher-php-server
```

---

## 🎯 المخرجات النهائية

بعد إكمال جميع المراحل، سيكون لديك:

### ✅ نظام حضور وانصراف كامل
- تسجيل دخول/خروج
- حساب ساعات العمل
- تتبع الإضافي
- تقارير حضور شهرية

### ✅ نظام إنتاج متقدم
- QR Code للعمال
- تتبع إنتاج بالقطعة
- Auto-assign ذكي
- تسجيل تلقائي للوقت

### ✅ نظام رواتب ذكي
- حساب تلقائي من البيانات الفعلية
- مكافآت وخصومات ذكية
- قيود محاسبية تلقائية
- كشوف رواتب PDF

### ✅ نظام جودة متكامل
- فحص قبل الاعتماد
- تسجيل العيوب
- إعادة للإنتاج
- تقارير جودة

### ✅ تصدير احترافي
- فواتير PDF
- تقارير Excel
- كشوف رواتب
- تقارير إنتاج

### ✅ تنبيهات وأتمتة
- إشعارات فورية
- مهام مجدولة
- تقارير دورية
- تحديثات لحظية

---

## 📈 مقاييس النجاح

### قبل التنفيذ:
- ❌ حساب عمالة تقريبي (30% ثابت)
- ❌ لا يوجد تتبع حضور
- ❌ لا يوجد QR
- ❌ رواتب يدوية
- ❌ لا يوجد فحص جودة منظم
- ❌ لا توجد تقارير PDF

### بعد التنفيذ:
- ✅ حساب عمالة دقيق من بيانات فعلية
- ✅ تتبع حضور تلقائي
- ✅ QR للعمال
- ✅ رواتب تلقائية
- ✅ فحص جودة منظم
- ✅ تقارير PDF/Excel احترافية

---

## 🚀 البدء في التنفيذ

### الخطوة الأولى:
```bash
git checkout -b feature/production-workflow-complete
```

### ترتيب التنفيذ المقترح:
1. ابدأ بالمرحلة 1 (Attendance + Production Logs)
2. ثم المرحلة 2 (QR + Auto-assign)
3. ثم المرحلة 3 (Payroll Integration)
4. ثم المرحلة 4 (Quality + PDF)
5. أخيراً المرحلة 5 (Alerts + Automation)

### Testing بعد كل مرحلة:
- ✅ Unit tests
- ✅ Feature tests
- ✅ Manual testing
- ✅ User acceptance testing

---

## 📞 الدعم والمتابعة

### أثناء التنفيذ:
- مراجعة أسبوعية للتقدم
- حل المشاكل الفنية
- اختبار الميزات الجديدة
- تدريب المستخدمين

### بعد التنفيذ:
- دعم فني لمدة شهر
- تحديثات وتحسينات
- تدريب إضافي
- توثيق كامل

---

**آخر تحديث:** {{ now()->format('Y-m-d') }}  
**الحالة:** 📋 جاهز للتنفيذ  
**المدة المتوقعة:** 6-8 أسابيع  
**نسبة الإكمال بعد التنفيذ:** 100% ✅

