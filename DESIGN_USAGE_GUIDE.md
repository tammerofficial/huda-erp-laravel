# 📖 دليل استخدام معايير التصميم

## 📁 الملفات المتوفرة

### 1. DESIGN_STANDARDS.md
**الوصف**: الدليل الشامل لمعايير التصميم
**المحتوى**:
- الألوان والثيم الأساسي
- معايير صفحات Index, Create, Edit, Show
- المكونات المشتركة (Buttons, Cards, Forms, etc.)
- قواعد التسمية والأيقونات
- Checklist للصفحات الجديدة

**متى تستخدمه**:
- عند إنشاء صفحة جديدة من الصفر
- للتأكد من الالتزام بالمعايير
- للبحث عن component معين

### 2. QUICK_TEMPLATES.md
**الوصف**: نماذج سريعة جاهزة للنسخ
**المحتوى**:
- نموذج كامل لصفحة Index
- نموذج كامل لصفحة Create
- نموذج كامل لصفحة Edit
- نموذج كامل لصفحة Show

**متى تستخدمه**:
- عند الحاجة لنسخ template سريع
- كنقطة بداية لصفحة جديدة
- للمقارنة مع صفحة موجودة

### 3. huda-styles.css
**الوصف**: ملف CSS مركزي للأنماط المخصصة
**المحتوى**:
- Variables للألوان والأبعاد
- Classes جاهزة للأزرار
- Styles للكروت والجداول
- Utility classes

**كيفية الاستخدام**:
```blade
<!-- في ملف layouts/app.blade.php -->
<link rel="stylesheet" href="{{ asset('css/huda-styles.css') }}">
```

---

## 🚀 كيفية إنشاء صفحة جديدة

### الخطوة 1: تحديد نوع الصفحة
حدد أي نوع من الصفحات تريد إنشاءه:
- Index (قائمة)
- Create (إنشاء)
- Edit (تعديل)
- Show (عرض)

### الخطوة 2: نسخ النموذج
افتح ملف `QUICK_TEMPLATES.md` وانسخ النموذج المناسب

### الخطوة 3: التخصيص
عدل النموذج حسب احتياجاتك:
1. غير اسم الـ resource (مثل: materials, orders, products)
2. عدل الحقول حسب نموذج البيانات
3. أضف/احذف الأعمدة في الجداول
4. حدث الأيقونات المناسبة

### الخطوة 4: التحقق
استخدم Checklist في `DESIGN_STANDARDS.md` للتأكد من:
- ✅ الألوان صحيحة
- ✅ الأيقونات مناسبة
- ✅ الأزرار في المكان الصحيح
- ✅ التصميم responsive
- ✅ Error handling موجود

---

## 📝 أمثلة عملية

### مثال 1: إنشاء صفحة Index للمنتجات

```blade
@extends('layouts.app')

@section('title', 'Products Management')
@section('page-title', 'Products Management')

@section('content')
<div x-data="productIndex()">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    🏭 Products Management
                </h2>
                <p class="text-gray-600 mt-1">Manage all your products</p>
            </div>
            <div>
                <a href="{{ route('products.create') }}" 
                   class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Product
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ number_format($product->price, 3) }} KWD</td>
                    <td>
                        <span class="badge badge-{{ $product->is_active ? 'success' : 'danger' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('products.show', $product) }}" 
                               class="text-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}" 
                               class="text-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
```

### مثال 2: استخدام CSS Classes

```blade
<!-- زر أساسي -->
<button class="btn-primary">
    <i class="fas fa-save mr-2"></i>
    Save
</button>

<!-- بطاقة إحصائيات -->
<div class="stat-card">
    <div class="stat-card-icon bg-info">
        <i class="fas fa-box text-white"></i>
    </div>
    <div class="stat-card-value">150</div>
    <div class="stat-card-label">Total Products</div>
</div>

<!-- حقل إدخال -->
<div class="form-group">
    <label class="form-label form-label-required">Product Name</label>
    <input type="text" 
           name="name" 
           class="form-control"
           placeholder="Enter product name">
    <span class="form-help">Enter a unique product name</span>
</div>

<!-- Badge -->
<span class="badge badge-success">Active</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-danger">Inactive</span>

<!-- Alert -->
<div class="alert alert-success">
    <i class="fas fa-check-circle mr-2"></i>
    Product created successfully!
</div>
```

---

## 🎨 الألوان السريعة

### الألوان الأساسية
```
Gold:        #d4af37  (للعناوين المهمة والأيقونات الخاصة)
Dark:        #1a1a1a  (للنصوص الأساسية)
Light:       #f8f8f8  (للخلفيات الثانوية)
```

### ألوان الحالات
```
Success:     #10b981  (أخضر - نجاح، نشط)
Warning:     #f59e0b  (برتقالي - تحذير، معلق)
Danger:      #ef4444  (أحمر - خطر، غير نشط)
Info:        #3b82f6  (أزرق - معلومات، أساسي)
Secondary:   #6b7280  (رمادي - ثانوي، إلغاء)
```

---

## 🔧 نصائح سريعة

### 1. استخدم Alpine.js للتفاعل
```blade
<div x-data="{ showModal: false }">
    <button @click="showModal = true" class="btn-primary">
        Open Modal
    </button>
    
    <div x-show="showModal" x-cloak>
        <!-- Modal content -->
    </div>
</div>
```

### 2. استخدم Grid للتخطيط
```blade
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>Column 1</div>
    <div>Column 2</div>
</div>
```

### 3. أضف أيقونات Font Awesome
```blade
<i class="fas fa-icon-name"></i>
```

### 4. استخدم old() للفورمات
```blade
<!-- Create -->
value="{{ old('field_name') }}"

<!-- Edit -->
value="{{ old('field_name', $item->field_name) }}"
```

### 5. تعامل مع الأخطاء
```blade
<input class="form-control @error('field_name') is-invalid @enderror">
@error('field_name')
    <span class="form-error">{{ $message }}</span>
@enderror
```

---

## ✅ Checklist سريع

عند إنشاء صفحة جديدة، تأكد من:

### Index Page
- [ ] Header مع أيقونة ووصف
- [ ] زر "Add New" أزرق
- [ ] جدول مع hover effect
- [ ] أزرار Actions (view, edit, delete)
- [ ] Status badges بالألوان الصحيحة
- [ ] Empty state
- [ ] Pagination

### Create/Edit Page
- [ ] Header مع الأزرار (Cancel, Save)
- [ ] Sections منظمة
- [ ] Labels مع * للحقول المطلوبة
- [ ] Validation وعرض الأخطاء
- [ ] Placeholders واضحة
- [ ] Help text للحقول المعقدة

### Show Page
- [ ] Header مع gradient background
- [ ] Info items مع hover effect
- [ ] Badges للحالات
- [ ] أزرار Edit و Back
- [ ] عرض الصورة (إن وجدت)
- [ ] جداول للبيانات المرتبطة

---

## 📞 الدعم

إذا كان لديك أي استفسار:
1. راجع `DESIGN_STANDARDS.md` للتفاصيل الكاملة
2. ابحث في `QUICK_TEMPLATES.md` عن نموذج مشابه
3. استخدم classes من `huda-styles.css`

---

**آخر تحديث**: 22 أكتوبر 2025  
**الإصدار**: 1.0.0

