# 🏗️ Huda Fashion ERP - Technical Architecture

## 📋 System Architecture Overview

This document provides a comprehensive technical overview of the Huda Fashion ERP system architecture, including database design, API structure, security implementation, and performance considerations.

---

## 🗄️ Database Architecture

### **Core Tables Structure**

#### **1. Users & Authentication**
```sql
-- users table (Laravel default)
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP,
    password VARCHAR(255),
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- roles and permissions (Spatie Laravel Permission)
CREATE TABLE roles (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    guard_name VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE permissions (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    guard_name VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### **2. Employee Management**
```sql
-- employees table (comprehensive employee data)
CREATE TABLE employees (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    employee_id VARCHAR(255) UNIQUE,
    position VARCHAR(255),
    department VARCHAR(255),
    employment_status ENUM('active', 'inactive', 'terminated'),
    hire_date DATE,
    salary_type ENUM('monthly', 'per_piece', 'hourly'),
    base_salary DECIMAL(10,3),
    overtime_rate DECIMAL(8,3),
    bonus_rate DECIMAL(8,3),
    payment_method VARCHAR(255),
    bank_name VARCHAR(255),
    bank_account_number VARCHAR(255),
    tax_id VARCHAR(255),
    attendance_device_id VARCHAR(255),
    efficiency_rating DECIMAL(5,2) DEFAULT 100,
    current_workload INT DEFAULT 0,
    qr_code TEXT,
    qr_enabled BOOLEAN DEFAULT FALSE,
    qr_image_path VARCHAR(255),
    nationality VARCHAR(255),
    civil_id VARCHAR(255),
    passport_number VARCHAR(255),
    passport_expiry DATE,
    blood_type VARCHAR(10),
    emergency_contact_name VARCHAR(255),
    emergency_contact_phone VARCHAR(255),
    emergency_contact_relation VARCHAR(255),
    probation_end_date DATE,
    work_schedule JSON,
    vacation_days_entitled INT DEFAULT 0,
    vacation_days_used INT DEFAULT 0,
    sick_days_used INT DEFAULT 0,
    documents JSON,
    profile_photo VARCHAR(255),
    id_card_front VARCHAR(255),
    id_card_back VARCHAR(255),
    passport_photo VARCHAR(255),
    visa_photo VARCHAR(255),
    contract_document VARCHAR(255),
    medical_certificate VARCHAR(255),
    other_documents JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_employment_status (employment_status),
    INDEX idx_department (department),
    INDEX idx_employee_id (employee_id)
);
```

#### **3. Attendance System**
```sql
-- attendance_records table
CREATE TABLE attendance_records (
    id BIGINT PRIMARY KEY,
    employee_id BIGINT,
    date DATE,
    check_in TIME,
    check_out TIME,
    hours_worked DECIMAL(5,2),
    overtime_hours DECIMAL(5,2),
    status ENUM('present', 'absent', 'late', 'half_day'),
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    INDEX idx_employee_date (employee_id, date),
    INDEX idx_date (date),
    INDEX idx_status (status)
);
```

#### **4. Production Management**
```sql
-- production_logs table
CREATE TABLE production_logs (
    id BIGINT PRIMARY KEY,
    employee_id BIGINT,
    production_stage_id BIGINT,
    product_id BIGINT,
    pieces_completed INT,
    rate_per_piece DECIMAL(8,3),
    start_time DATETIME,
    end_time DATETIME,
    duration_minutes INT,
    earnings DECIMAL(10,3),
    expected_duration INT,
    efficiency_rate DECIMAL(5,2),
    quality_status ENUM('pending', 'approved', 'rejected'),
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_employee_created (employee_id, created_at),
    INDEX idx_product (product_id),
    INDEX idx_quality_status (quality_status)
);
```

#### **5. Quality Control**
```sql
-- quality_checks table
CREATE TABLE quality_checks (
    id BIGINT PRIMARY KEY,
    production_order_id BIGINT,
    product_id BIGINT,
    inspector_id BIGINT,
    status ENUM('pending', 'passed', 'failed'),
    items_checked INT,
    items_passed INT,
    items_failed INT,
    defects JSON,
    notes TEXT,
    inspection_date DATETIME,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (production_order_id) REFERENCES production_orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (inspector_id) REFERENCES employees(id),
    INDEX idx_production_order (production_order_id),
    INDEX idx_inspector (inspector_id),
    INDEX idx_status (status)
);
```

#### **6. Employee Events**
```sql
-- employee_events table
CREATE TABLE employee_events (
    id BIGINT PRIMARY KEY,
    employee_id BIGINT,
    title VARCHAR(255),
    description TEXT,
    event_date DATE,
    start_time TIME,
    end_time TIME,
    event_type ENUM('vacation', 'sick_leave', 'holiday', 'meeting', 'training', 'performance_review', 'contract_renewal', 'probation_end', 'birthday', 'anniversary'),
    status ENUM('active', 'completed', 'cancelled'),
    is_recurring BOOLEAN DEFAULT FALSE,
    recurring_type ENUM('daily', 'weekly', 'monthly', 'yearly'),
    color VARCHAR(7),
    is_all_day BOOLEAN DEFAULT FALSE,
    reminder_settings JSON,
    created_by BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_employee_date (employee_id, event_date),
    INDEX idx_event_type (event_type),
    INDEX idx_status (status)
);
```

---

## 🔌 API Architecture

### **RESTful API Design**

#### **1. Authentication Endpoints**
```php
// routes/api.php
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
});
```

#### **2. Employee Management API**
```php
// Employee CRUD operations
Route::apiResource('employees', EmployeeController::class);
Route::post('/employees/{employee}/generate-qr', [EmployeeController::class, 'generateQR']);
Route::post('/employees/{employee}/toggle-qr', [EmployeeController::class, 'toggleQR']);
Route::post('/employees/generate-all-qr', [EmployeeController::class, 'generateAllQR']);
```

#### **3. Attendance API**
```php
// Attendance management
Route::apiResource('attendance', AttendanceController::class);
Route::post('/attendance/bulk-check-in', [AttendanceController::class, 'bulkCheckIn']);
Route::post('/attendance/bulk-check-out', [AttendanceController::class, 'bulkCheckOut']);
Route::get('/attendance/report/{month}', [AttendanceController::class, 'monthlyReport']);
```

#### **4. Production API**
```php
// Production management
Route::apiResource('production-logs', ProductionLogController::class);
Route::post('/production-logs/{log}/complete', [ProductionLogController::class, 'complete']);
Route::post('/production-logs/{log}/approve', [ProductionLogController::class, 'approve']);
Route::post('/production-logs/{log}/reject', [ProductionLogController::class, 'reject']);
```

#### **5. Quality Control API**
```php
// Quality management
Route::apiResource('quality-checks', QualityCheckController::class);
Route::get('/quality-checks/inspect/{productionOrder}', [QualityCheckController::class, 'inspect']);
Route::post('/quality-checks/inspect/{productionOrder}', [QualityCheckController::class, 'submitInspection']);
```

#### **6. QR Code API**
```php
// QR Code operations
Route::prefix('qr')->group(function () {
    Route::post('/validate', [QRController::class, 'validate']);
    Route::post('/check-in', [QRController::class, 'checkIn']);
    Route::post('/check-out', [QRController::class, 'checkOut']);
    Route::post('/start-stage', [QRController::class, 'startStage']);
    Route::post('/complete-stage', [QRController::class, 'completeStage']);
});
```

---

## 🔒 Security Architecture

### **1. Authentication & Authorization**

#### **JWT Token Implementation**
```php
// config/auth.php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],

// JWT Middleware
Route::middleware(['auth:api'])->group(function () {
    // Protected routes
});
```

#### **Role-Based Access Control**
```php
// app/Http/Middleware/CheckPermission.php
class CheckPermission {
    public function handle($request, Closure $next, $permission) {
        if (!auth()->user()->can($permission)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}

// Usage in routes
Route::middleware(['auth:api', 'permission:manage_employees'])->group(function () {
    Route::apiResource('employees', EmployeeController::class);
});
```

### **2. Data Validation**

#### **Request Validation**
```php
// app/Http/Requests/AttendanceRequest.php
class AttendanceRequest extends FormRequest {
    public function rules() {
        return [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'notes' => 'nullable|string|max:1000'
        ];
    }
}
```

#### **Model Validation**
```php
// app/Models/AttendanceRecord.php
protected static function boot() {
    parent::boot();
    
    static::saving(function ($record) {
        // Validate business rules
        if ($record->check_in && $record->check_out) {
            $checkIn = Carbon::parse($record->date . ' ' . $record->check_in);
            $checkOut = Carbon::parse($record->date . ' ' . $record->check_out);
            
            if ($checkOut->lte($checkIn)) {
                throw new \Exception('Check out time must be after check in time');
            }
        }
    });
}
```

### **3. Data Encryption**

#### **Sensitive Data Encryption**
```php
// app/Models/Employee.php
protected $casts = [
    'bank_account_number' => 'encrypted',
    'tax_id' => 'encrypted',
    'passport_number' => 'encrypted',
    'civil_id' => 'encrypted'
];
```

---

## ⚡ Performance Architecture

### **1. Database Optimization**

#### **Indexing Strategy**
```sql
-- Composite indexes for common queries
CREATE INDEX idx_attendance_employee_date ON attendance_records(employee_id, date);
CREATE INDEX idx_production_employee_created ON production_logs(employee_id, created_at);
CREATE INDEX idx_quality_production_order ON quality_checks(production_order_id);

-- Partial indexes for filtered queries
CREATE INDEX idx_active_employees ON employees(employment_status) WHERE employment_status = 'active';
CREATE INDEX idx_pending_quality ON quality_checks(status) WHERE status = 'pending';
```

#### **Query Optimization**
```php
// Eager loading to prevent N+1 queries
public function index() {
    return AttendanceRecord::with(['employee.user'])
        ->select('attendance_records.*')
        ->join('employees', 'attendance_records.employee_id', '=', 'employees.id')
        ->where('employees.employment_status', 'active')
        ->orderBy('attendance_records.date', 'desc')
        ->paginate(20);
}
```

### **2. Caching Strategy**

#### **Redis Caching**
```php
// config/cache.php
'default' => env('CACHE_DRIVER', 'redis'),

'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
    ],
],

// Cache implementation
public function getStatistics() {
    return Cache::remember('attendance_statistics', 3600, function () {
        return [
            'total_employees' => Employee::count(),
            'present_today' => AttendanceRecord::where('date', today())->count(),
            'absent_today' => Employee::count() - AttendanceRecord::where('date', today())->count(),
            'late_today' => AttendanceRecord::where('date', today())->where('status', 'late')->count()
        ];
    });
}
```

#### **Model Caching**
```php
// app/Models/Employee.php
public function getAttendanceRecordsAttribute() {
    return Cache::remember("employee_{$this->id}_attendance", 1800, function () {
        return $this->attendanceRecords()->get();
    });
}
```

### **3. Queue System**

#### **Background Job Processing**
```php
// config/queue.php
'default' => env('QUEUE_CONNECTION', 'redis'),

'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],

// Job implementation
class SendLowStockAlert implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle() {
        // Send alert logic
    }
}
```

---

## 📱 Frontend Architecture

### **1. Blade Template System**

#### **Component-Based Architecture**
```php
<!-- resources/views/components/statistics-card.blade.php -->
<div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-400">{{ $title }}</p>
            <p class="text-2xl font-bold text-white">{{ $value }}</p>
            @if(isset($change))
                <p class="text-sm {{ $change >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ $change >= 0 ? '+' : '' }}{{ $change }}%
                </p>
            @endif
        </div>
        <div class="p-3 bg-{{ $color }}-500 bg-opacity-20 rounded-full">
            <svg class="w-6 h-6 text-{{ $color }}-400" fill="currentColor" viewBox="0 0 20 20">
                {!! $icon !!}
            </svg>
        </div>
    </div>
</div>
```

#### **Layout System**
```php
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Huda Fashion ERP') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white">
    <div class="flex h-screen">
        @include('layouts.partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('layouts.partials.header')
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-900">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
```

### **2. JavaScript Architecture**

#### **Alpine.js Integration**
```javascript
// resources/js/app.js
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Global Alpine.js data
Alpine.data('attendanceManager', () => ({
    employees: [],
    selectedEmployees: [],
    date: new Date().toISOString().split('T')[0],
    checkIn: new Date().toTimeString().split(' ')[0].substring(0, 5),
    
    init() {
        this.loadEmployees();
    },
    
    async loadEmployees() {
        try {
            const response = await fetch('/api/employees');
            this.employees = await response.json();
        } catch (error) {
            console.error('Error loading employees:', error);
        }
    },
    
    toggleEmployee(employeeId) {
        const index = this.selectedEmployees.indexOf(employeeId);
        if (index > -1) {
            this.selectedEmployees.splice(index, 1);
        } else {
            this.selectedEmployees.push(employeeId);
        }
    },
    
    async bulkCheckIn() {
        try {
            const response = await fetch('/attendance/bulk-check-in', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    employee_ids: this.selectedEmployees,
                    date: this.date,
                    check_in: this.checkIn
                })
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error during bulk check-in:', error);
        }
    }
}));

// Chart.js integration
Alpine.data('chartManager', () => ({
    chart: null,
    
    init() {
        this.createChart();
    },
    
    createChart() {
        const ctx = this.$refs.chart.getContext('2d');
        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Attendance',
                    data: [65, 59, 80, 81, 56, 55],
                    borderColor: 'rgb(212, 175, 55)',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: 'white'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    }
}));

window.Alpine = Alpine;
Alpine.start();
```

---

## 🔄 Integration Architecture

### **1. WooCommerce Integration**

#### **Sales Data Synchronization**
```php
// app/Console/Commands/SyncWooCommerceCommand.php
class SyncWooCommerceCommand extends Command {
    protected $signature = 'woocommerce:sync';
    protected $description = 'Sync sales data from WooCommerce';
    
    public function handle() {
        $woocommerce = new WooCommerceClient(
            config('woocommerce.url'),
            config('woocommerce.consumer_key'),
            config('woocommerce.consumer_secret')
        );
        
        $orders = $woocommerce->get('orders', [
            'per_page' => 100,
            'status' => 'completed',
            'after' => now()->subDays(30)->toISOString()
        ]);
        
        foreach ($orders as $order) {
            WooCommerceSale::updateOrCreate(
                ['order_id' => $order['id']],
                [
                    'customer_name' => $order['billing']['first_name'] . ' ' . $order['billing']['last_name'],
                    'total' => $order['total'],
                    'currency' => $order['currency'],
                    'order_date' => $order['date_created'],
                    'status' => $order['status'],
                    'payment_method' => $order['payment_method_title'],
                    'shipping_cost' => $order['shipping_total']
                ]
            );
        }
        
        $this->info('WooCommerce sync completed');
    }
}
```

### **2. Payment Gateway Integration**

#### **Payment Processing**
```php
// app/Services/PaymentService.php
class PaymentService {
    public function processPayment($amount, $currency, $gateway) {
        switch ($gateway) {
            case 'knet':
                return $this->processKNETPayment($amount, $currency);
            case 'visa':
                return $this->processVisaPayment($amount, $currency);
            case 'mastercard':
                return $this->processMastercardPayment($amount, $currency);
            default:
                throw new \Exception('Unsupported payment gateway');
        }
    }
    
    private function processKNETPayment($amount, $currency) {
        // KNET payment processing logic
        $response = Http::post('https://api.knet.com.kw/payment', [
            'amount' => $amount,
            'currency' => $currency,
            'merchant_id' => config('payment.knet.merchant_id'),
            'api_key' => config('payment.knet.api_key')
        ]);
        
        return $response->json();
    }
}
```

---

## 📊 Monitoring & Logging

### **1. Application Monitoring**

#### **Performance Monitoring**
```php
// app/Http/Middleware/PerformanceMiddleware.php
class PerformanceMiddleware {
    public function handle($request, Closure $next) {
        $start = microtime(true);
        $startMemory = memory_get_usage();
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        $memoryUsage = memory_get_usage() - $startMemory;
        
        if ($duration > 2) {
            Log::warning('Slow request detected', [
                'url' => $request->url(),
                'method' => $request->method(),
                'duration' => $duration,
                'memory_usage' => $memoryUsage,
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }
        
        return $response;
    }
}
```

#### **Error Tracking**
```php
// app/Exceptions/Handler.php
public function register() {
    $this->reportable(function (Throwable $e) {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }
    });
}
```

### **2. Database Monitoring**

#### **Query Performance Tracking**
```php
// app/Providers/AppServiceProvider.php
public function boot() {
    if (config('app.debug')) {
        DB::listen(function ($query) {
            if ($query->time > 1000) { // Log queries taking more than 1 second
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time
                ]);
            }
        });
    }
}
```

---

## 🚀 Deployment Architecture

### **1. Production Environment**

#### **Server Configuration**
```nginx
# nginx.conf
server {
    listen 80;
    server_name huda-erp.com;
    root /var/www/huda-erp/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

#### **PHP-FPM Configuration**
```ini
; /etc/php/8.3/fpm/pool.d/www.conf
[www]
user = www-data
group = www-data
listen = /var/run/php/php8.3-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 1000
```

### **2. Database Configuration**

#### **MySQL Optimization**
```ini
# /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
query_cache_size = 64M
query_cache_type = 1
max_connections = 200
```

### **3. Caching Configuration**

#### **Redis Configuration**
```ini
# /etc/redis/redis.conf
maxmemory 512mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

---

## 🔧 Maintenance & Updates

### **1. Backup Strategy**

#### **Database Backup**
```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p huda_erp > /backups/database_$DATE.sql
gzip /backups/database_$DATE.sql
find /backups -name "database_*.sql.gz" -mtime +7 -delete
```

#### **File Backup**
```bash
#!/bin/bash
# file_backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf /backups/files_$DATE.tar.gz /var/www/huda-erp
find /backups -name "files_*.tar.gz" -mtime +7 -delete
```

### **2. Update Strategy**

#### **Zero-Downtime Deployment**
```bash
#!/bin/bash
# deploy.sh
php artisan down --message="System maintenance in progress"
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

---

## 📈 Scalability Considerations

### **1. Horizontal Scaling**

#### **Load Balancer Configuration**
```nginx
# nginx.conf
upstream huda_erp {
    server 192.168.1.10:80;
    server 192.168.1.11:80;
    server 192.168.1.12:80;
}

server {
    listen 80;
    server_name huda-erp.com;
    
    location / {
        proxy_pass http://huda_erp;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### **2. Database Scaling**

#### **Read Replicas**
```php
// config/database.php
'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'huda_erp'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
    
    'mysql_read' => [
        'driver' => 'mysql',
        'host' => env('DB_READ_HOST', '127.0.0.1'),
        'port' => env('DB_READ_PORT', '3306'),
        'database' => env('DB_DATABASE', 'huda_erp'),
        'username' => env('DB_READ_USERNAME', 'root'),
        'password' => env('DB_READ_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
],
```

---

## 🎯 Conclusion

The Huda Fashion ERP system is built with a robust, scalable architecture that includes:

- **Comprehensive Database Design** with proper indexing and relationships
- **RESTful API Architecture** for mobile and third-party integrations
- **Security-First Approach** with authentication, authorization, and data encryption
- **Performance Optimization** with caching, queuing, and database optimization
- **Modern Frontend** with component-based Blade templates and Alpine.js
- **Production-Ready Deployment** with monitoring, logging, and backup strategies

The system is designed to handle the complete fashion manufacturing workflow while maintaining high performance, security, and scalability.

---

**Document Version:** 1.0  
**Last Updated:** October 22, 2025  
**Author:** Huda Fashion ERP Development Team
