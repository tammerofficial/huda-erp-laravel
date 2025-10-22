# 🎨 معايير التصميم الموحد - Huda ERP System

## 📋 جدول المحتويات
1. [الألوان والثيم](#الألوان-والثيم)
2. [معايير صفحة Index (القوائم)](#معايير-صفحة-index-القوائم)
3. [معايير صفحة Create (الإنشاء)](#معايير-صفحة-create-الإنشاء)
4. [معايير صفحة Edit (التعديل)](#معايير-صفحة-edit-التعديل)
5. [معايير صفحة Show (العرض)](#معايير-صفحة-show-العرض)
6. [مكونات مشتركة](#مكونات-مشتركة)
7. [قواعد البيانات والأيقونات](#قواعد-البيانات-والأيقونات)

---

## 🎨 الألوان والثيم

### الألوان الأساسية
```css
/* Primary Colors */
--luxury-gold: #d4af37;
--luxury-dark: #1a1a1a;
--luxury-dark-secondary: #2d2d2d;
--luxury-light: #f8f8f8;

/* Status Colors */
--success: #10b981;     /* Green */
--warning: #f59e0b;     /* Orange/Amber */
--danger: #ef4444;      /* Red */
--info: #3b82f6;        /* Blue */
--secondary: #6b7280;   /* Gray */

/* Background Colors */
--bg-primary: #ffffff;
--bg-secondary: #f8f8f8;
--bg-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);

/* Text Colors */
--text-primary: #1a1a1a;
--text-secondary: #6b7280;
--text-muted: #9ca3af;
```

### الخطوط والأحجام
```css
/* Font Weights */
--font-normal: 400;
--font-medium: 500;
--font-semibold: 600;
--font-bold: 700;

/* Font Sizes */
--text-xs: 0.75rem;      /* 12px */
--text-sm: 0.875rem;     /* 14px */
--text-base: 1rem;       /* 16px */
--text-lg: 1.125rem;     /* 18px */
--text-xl: 1.25rem;      /* 20px */
--text-2xl: 1.5rem;      /* 24px */
--text-3xl: 1.875rem;    /* 30px */
```

---

## 📊 معايير صفحة Index (القوائم)

### البنية العامة
```blade
@extends('layouts.app')

@section('title', 'Page Title')
@section('page-title', 'Page Title')

@section('content')
<div class="container-fluid"> <!-- أو x-data إذا كان Alpine.js مطلوب -->
    
    <!-- 1. Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    🎯 [Icon] [Page Title]
                </h2>
                <p class="text-gray-600 mt-1">[Page Description]</p>
            </div>
            <div class="flex space-x-3">
                <!-- Action buttons -->
                <a href="{{ route('resource.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>
                    Add New
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Search and Filter Section (اختياري) -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search and filters -->
        </div>
    </div>

    <!-- 3. Stats Cards (اختياري) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Stat cards -->
    </div>

    <!-- 4. Main Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Header
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <!-- Table rows -->
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 5. Pagination -->
        @if($items->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $items->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
```

### مكونات الجدول

#### رأس الجدول (Table Header)
```blade
<thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Column Name
        </th>
    </tr>
</thead>
```

#### صف الجدول (Table Row)
```blade
<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap">
        <!-- Content -->
    </td>
</tr>
```

#### الأزرار في الجدول (Action Buttons)
```blade
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    <div class="flex space-x-2">
        <!-- View -->
        <a href="{{ route('resource.show', $item) }}" 
           class="text-blue-600 hover:text-blue-900"
           title="View">
            <i class="fas fa-eye"></i>
        </a>
        
        <!-- Edit -->
        <a href="{{ route('resource.edit', $item) }}" 
           class="text-indigo-600 hover:text-indigo-900"
           title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        
        <!-- Delete -->
        <button onclick="confirmDelete({{ $item->id }})"
                class="text-red-600 hover:text-red-900"
                title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</td>
```

#### Badges (الحالات)
```blade
<!-- Success Badge -->
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
    Active
</span>

<!-- Warning Badge -->
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
    Pending
</span>

<!-- Danger Badge -->
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
    Inactive
</span>

<!-- Info Badge -->
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
    Draft
</span>
```

#### Empty State (لا توجد بيانات)
```blade
@forelse($items as $item)
    <!-- Table rows -->
@empty
    <tr>
        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
            <div class="flex flex-col items-center py-8">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-2"></i>
                <p class="text-lg">No records found</p>
                <a href="{{ route('resource.create') }}" 
                   class="mt-2 text-blue-600 hover:text-blue-800">
                    Add your first record
                </a>
            </div>
        </td>
    </tr>
@endforelse
```

---

## ➕ معايير صفحة Create (الإنشاء)

### البنية العامة
```blade
@extends('layouts.app')

@section('title', 'Create [Resource]')
@section('page-title', 'Create [Resource]')

@section('content')
<div class="container-fluid">
    <form action="{{ route('resource.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. Header Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        ➕ Create New [Resource]
                    </h2>
                    <p class="text-gray-600 mt-1">Fill in the information below</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('resource.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. Form Sections -->
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Form fields -->
            </div>
        </div>

        <!-- Additional Sections as needed -->

    </form>
</div>
@endsection
```

### مكونات الفورم

#### حقل نصي (Text Input)
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Field Name <span class="text-red-500">*</span>
    </label>
    <input type="text" 
           name="field_name" 
           value="{{ old('field_name') }}"
           required
           placeholder="Enter field name"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('field_name') border-red-500 @enderror">
    @error('field_name')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
```

#### قائمة منسدلة (Select)
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Select Option <span class="text-red-500">*</span>
    </label>
    <select name="field_name" 
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('field_name') border-red-500 @enderror">
        <option value="">Select Option</option>
        @foreach($options as $option)
            <option value="{{ $option->id }}" {{ old('field_name') == $option->id ? 'selected' : '' }}>
                {{ $option->name }}
            </option>
        @endforeach
    </select>
    @error('field_name')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
```

#### حقل رقمي (Number Input)
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Quantity <span class="text-red-500">*</span>
    </label>
    <input type="number" 
           name="quantity" 
           value="{{ old('quantity') }}"
           min="0"
           step="0.01"
           required
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity') border-red-500 @enderror">
    @error('quantity')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
```

#### منطقة نصية (Textarea)
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Description
    </label>
    <textarea name="description" 
              rows="4"
              placeholder="Enter description..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
    @error('description')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
```

#### Checkbox
```blade
<div class="flex items-center">
    <input type="checkbox" 
           id="is_active" 
           name="is_active" 
           value="1"
           {{ old('is_active', true) ? 'checked' : '' }}
           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
    <label for="is_active" class="ml-2 block text-sm text-gray-900">
        Active Status
    </label>
</div>
```

#### رفع ملف (File Upload)
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Upload Image
    </label>
    <input type="file" 
           name="image" 
           accept="image/*"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
    @error('image')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
    <p class="mt-1 text-sm text-gray-500">
        Accepted formats: JPG, PNG, GIF. Max size: 2MB
    </p>
</div>
```

#### تاريخ (Date Input)
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Date <span class="text-red-500">*</span>
    </label>
    <input type="date" 
           name="date" 
           value="{{ old('date', date('Y-m-d')) }}"
           required
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror">
    @error('date')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
```

---

## ✏️ معايير صفحة Edit (التعديل)

### البنية العامة
```blade
@extends('layouts.app')

@section('title', 'Edit [Resource]')
@section('page-title', 'Edit [Resource]')

@section('content')
<div class="container-fluid">
    <form action="{{ route('resource.update', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. Header Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        ✏️ Edit [Resource]: {{ $item->name }}
                    </h2>
                    <p class="text-gray-600 mt-1">Update information and specifications</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('resource.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. Form Sections - نفس البنية من Create -->
        <!-- استخدم old('field', $item->field) بدلاً من old('field') فقط -->

    </form>
</div>
@endsection
```

### الفرق بين Create و Edit
```blade
<!-- في Create -->
value="{{ old('field_name') }}"

<!-- في Edit -->
value="{{ old('field_name', $item->field_name) }}"

<!-- في Checkbox Create -->
{{ old('is_active', true) ? 'checked' : '' }}

<!-- في Checkbox Edit -->
{{ old('is_active', $item->is_active) ? 'checked' : '' }}
```

---

## 👁️ معايير صفحة Show (العرض)

### البنية العامة
```blade
@extends('layouts.app')

@section('title', '[Resource] Details')
@section('page-title', '[Resource] Details')

@section('content')
<div class="container-fluid">
    
    <!-- 1. Header Card -->
    <div class="luxury-card mb-4">
        <div class="p-4 border-bottom" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1" style="color: #d4af37; font-weight: 600;">
                        <i class="fas fa-icon"></i> {{ $item->name }}
                    </h3>
                    <p class="mb-0" style="color: #ffffff; opacity: 0.9;">
                        <span class="badge bg-secondary">{{ $item->code }}</span>
                        @if($item->is_active)
                            <span class="badge bg-success ms-2">Active</span>
                        @else
                            <span class="badge bg-danger ms-2">Inactive</span>
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('resource.edit', $item) }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('resource.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- 2. Left Column - Image & Quick Stats -->
        <div class="col-lg-4">
            <!-- Image Card -->
            <div class="luxury-card mb-4">
                <div class="p-4">
                    @if($item->image_url)
                        <img src="{{ Storage::url($item->image_url) }}" 
                             alt="{{ $item->name }}" 
                             class="img-fluid rounded" 
                             style="width: 100%; object-fit: cover; max-height: 300px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center rounded" 
                             style="height: 300px; background: linear-gradient(135deg, #f8f8f8 0%, #e9e9e9 100%);">
                            <i class="fas fa-image" style="font-size: 4rem; color: #d4af37; opacity: 0.3;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-chart-bar"></i> Quick Stats
                    </h5>
                </div>
                <div class="p-4">
                    <!-- Stats content -->
                </div>
            </div>
        </div>

        <!-- 3. Right Column - Details -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-info-circle"></i> Basic Information
                    </h5>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Info items -->
                    </div>
                </div>
            </div>

            <!-- Additional Sections -->
        </div>
    </div>
</div>

<!-- Styles -->
<style>
.info-item {
    padding: 12px;
    border-radius: 8px;
    background-color: #fafafa;
    transition: all 0.3s ease;
}

.info-item:hover {
    background-color: #f5f5f5;
    transform: translateY(-2px);
}

.info-item label {
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.info-item p {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0;
}

.luxury-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
```

### Info Item Component
```blade
<div class="col-md-6">
    <div class="info-item">
        <label class="text-muted mb-1">Field Label</label>
        <p class="mb-0 fw-semibold">{{ $item->field_value ?? 'Not specified' }}</p>
    </div>
</div>
```

### جدول في صفحة Show
```blade
<div class="luxury-card mb-4">
    <div class="p-4 border-bottom">
        <h5 class="section-title mb-0">
            <i class="fas fa-list"></i> Related Items
        </h5>
    </div>
    <div class="p-4">
        @if($item->relatedItems->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f8f8;">
                        <tr>
                            <th>Column 1</th>
                            <th>Column 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->relatedItems as $related)
                        <tr>
                            <td>{{ $related->name }}</td>
                            <td>{{ $related->value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                <p class="text-muted">No related items found</p>
            </div>
        @endif
    </div>
</div>
```

---

## 🔧 مكونات مشتركة

### أزرار (Buttons)

#### Primary Button
```blade
<button type="button" class="btn-primary">
    <i class="fas fa-icon"></i>
    Button Text
</button>

<!-- CSS -->
.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #3b82f6;
    color: white;
    font-weight: 600;
    border-radius: 0.5rem;
    transition: all 0.3s;
}

.btn-primary:hover {
    background-color: #2563eb;
}
```

#### Secondary Button
```blade
<button type="button" class="btn-secondary">
    <i class="fas fa-icon"></i>
    Button Text
</button>

<!-- CSS -->
.btn-secondary {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #6b7280;
    color: white;
    font-weight: 600;
    border-radius: 0.5rem;
    transition: all 0.3s;
}

.btn-secondary:hover {
    background-color: #4b5563;
}
```

#### Outline Button
```blade
<button type="button" class="btn-outline">
    <i class="fas fa-icon"></i>
    Button Text
</button>

<!-- CSS -->
.btn-outline {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border: 2px solid #3b82f6;
    color: #3b82f6;
    font-weight: 600;
    border-radius: 0.5rem;
    background: transparent;
    transition: all 0.3s;
}

.btn-outline:hover {
    background-color: #3b82f6;
    color: white;
}
```

### بطاقات الإحصائيات (Stat Cards)
```blade
<div class="col-md-3">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Stat Title</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stat_value }}</h3>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-green-600">↑ 12%</span> from last month
                </p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-icon text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>
```

### رسائل التنبيه (Alert Messages)
```blade
<!-- Success Alert -->
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <p>{{ session('success') }}</p>
    </div>
</div>
@endif

<!-- Error Alert -->
@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <p>{{ session('error') }}</p>
    </div>
</div>
@endif

<!-- Warning Alert -->
@if(session('warning'))
<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded" role="alert">
    <div class="flex items-center">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <p>{{ session('warning') }}</p>
    </div>
</div>
@endif
```

### Loading States
```blade
<!-- Loading Spinner -->
<div class="flex justify-center items-center py-12">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
</div>

<!-- Skeleton Loader -->
<div class="animate-pulse">
    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
</div>
```

### Modal/Dialog
```blade
<!-- Modal Structure -->
<div x-show="showModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
         @click="showModal = false"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
            <!-- Close Button -->
            <button @click="showModal = false" 
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Modal Header -->
            <h3 class="text-lg font-bold text-gray-900 mb-4">Modal Title</h3>
            
            <!-- Modal Body -->
            <div class="mb-6">
                <p class="text-gray-600">Modal content goes here</p>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3">
                <button @click="showModal = false" class="btn-secondary">
                    Cancel
                </button>
                <button class="btn-primary">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>
```

### Tabs
```blade
<div x-data="{ activeTab: 'tab1' }">
    <!-- Tab Headers -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button @click="activeTab = 'tab1'"
                    :class="activeTab === 'tab1' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-icon mr-2"></i>
                Tab 1
            </button>
            <button @click="activeTab = 'tab2'"
                    :class="activeTab === 'tab2' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-icon mr-2"></i>
                Tab 2
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div x-show="activeTab === 'tab1'">
        Tab 1 Content
    </div>
    <div x-show="activeTab === 'tab2'" style="display: none;">
        Tab 2 Content
    </div>
</div>
```

---

## 🎯 قواعد البيانات والأيقونات

### الأيقونات حسب النوع (Font Awesome)

#### الموارد الأساسية
```
📦 Materials       → fas fa-cube
👥 Customers       → fas fa-users
🏢 Suppliers       → fas fa-truck
👤 Employees       → fas fa-user-tie
🏭 Products        → fas fa-box
📋 Orders          → fas fa-shopping-cart
💰 Invoices        → fas fa-file-invoice-dollar
🛒 Purchases       → fas fa-shopping-bag
🏗️ Production      → fas fa-industry
🏬 Warehouses      → fas fa-warehouse
```

#### العمليات
```
➕ Create          → fas fa-plus
✏️ Edit            → fas fa-edit
👁️ View            → fas fa-eye
🗑️ Delete          → fas fa-trash
💾 Save            → fas fa-save
❌ Cancel          → fas fa-times
🔄 Refresh         → fas fa-sync
📤 Export          → fas fa-download
📥 Import          → fas fa-upload
🔍 Search          → fas fa-search
🔔 Notification    → fas fa-bell
⚙️ Settings        → fas fa-cog
📊 Reports         → fas fa-chart-bar
💳 Payment         → fas fa-credit-card
✅ Approve         → fas fa-check
⚠️ Warning         → fas fa-exclamation-triangle
```

### حالات البيانات (Status)
```php
// Active/Inactive
'active' => 'bg-green-100 text-green-800'
'inactive' => 'bg-red-100 text-red-800'

// Order Status
'pending' => 'bg-yellow-100 text-yellow-800'
'processing' => 'bg-blue-100 text-blue-800'
'completed' => 'bg-green-100 text-green-800'
'cancelled' => 'bg-red-100 text-red-800'

// Payment Status
'paid' => 'bg-green-100 text-green-800'
'partial' => 'bg-yellow-100 text-yellow-800'
'unpaid' => 'bg-red-100 text-red-800'

// Priority
'low' => 'bg-gray-100 text-gray-800'
'normal' => 'bg-blue-100 text-blue-800'
'high' => 'bg-orange-100 text-orange-800'
'urgent' => 'bg-red-100 text-red-800'

// Stock Level
'in_stock' => 'bg-green-100 text-green-800'
'low_stock' => 'bg-yellow-100 text-yellow-800'
'out_of_stock' => 'bg-red-100 text-red-800'
```

### أرقام ومبالغ (Numbers & Currency)
```blade
<!-- Currency (KWD) -->
{{ number_format($amount, 3) }} KWD

<!-- Percentage -->
{{ number_format($percentage, 2) }}%

<!-- Quantity with Unit -->
{{ $quantity }} {{ $unit }}

<!-- Date Format -->
{{ $date->format('Y-m-d') }}           <!-- 2025-10-22 -->
{{ $date->format('d M Y') }}           <!-- 22 Oct 2025 -->
{{ $date->format('d/m/Y H:i') }}       <!-- 22/10/2025 14:30 -->
```

---

## 📝 قواعد التسمية (Naming Conventions)

### Routes
```php
// Resource routes
Route::resource('materials', MaterialController::class);

// Custom routes - use descriptive names
Route::post('materials/{material}/adjust-inventory', ...)->name('materials.adjust-inventory');
Route::get('orders/{order}/cost-breakdown', ...)->name('orders.cost-breakdown');
```

### Controllers
```php
// Standard CRUD methods
index()         // List all
create()        // Show create form
store()         // Save new record
show()          // Show single record
edit()          // Show edit form
update()        // Update record
destroy()       // Delete record

// Custom methods - use descriptive names
adjustInventory()
calculateCost()
syncFromWooCommerce()
```

### Views Directory Structure
```
resources/views/
├── [resource]/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   └── partials/
│       └── _form.blade.php
├── layouts/
│   ├── app.blade.php
│   └── partials/
│       ├── sidebar.blade.php
│       └── navbar.blade.php
└── components/
    ├── alert.blade.php
    └── stat-card.blade.php
```

---

## ✅ Checklist للصفحات الجديدة

### Index Page ✓
- [ ] Header with icon and description
- [ ] Search and filter section (if needed)
- [ ] "Add New" button (primary, blue)
- [ ] Table with proper styling
- [ ] Action buttons (view, edit, delete)
- [ ] Status badges with correct colors
- [ ] Empty state with icon
- [ ] Pagination
- [ ] Responsive design

### Create Page ✓
- [ ] Header with "Create New" title
- [ ] Cancel and Save buttons
- [ ] Form sections with clear headings
- [ ] Required fields marked with *
- [ ] Proper input types and validation
- [ ] Error messages styling
- [ ] Help text for complex fields
- [ ] Proper grid layout (1 or 2 columns)

### Edit Page ✓
- [ ] Header with "Edit [Resource]: [Name]"
- [ ] Pre-filled form values with old() helper
- [ ] Same structure as Create page
- [ ] Cancel and "Save Changes" buttons
- [ ] @method('PUT') directive

### Show Page ✓
- [ ] Luxury card header with gradient
- [ ] Left column: Image + Quick Stats
- [ ] Right column: Detailed information
- [ ] Info items with hover effect
- [ ] Related items in tables (if applicable)
- [ ] Edit and Back buttons
- [ ] Status badges
- [ ] Custom styles for luxury feel

---

## 🎨 CSS Classes Reference

### Layout Classes
```css
.container-fluid          /* Full width container */
.row                      /* Bootstrap row */
.col-md-{1-12}           /* Bootstrap columns */
.g-4                      /* Gap 1rem */
.mb-{1-6}                /* Margin bottom */
.p-{1-6}                 /* Padding */
```

### Flex & Grid
```css
.flex                     /* Display flex */
.justify-between         /* Space between */
.items-center            /* Align center */
.space-x-{1-6}          /* Horizontal spacing */
.grid                    /* Display grid */
.grid-cols-{1-12}       /* Grid columns */
.gap-{1-6}              /* Grid gap */
```

### Text Styling
```css
.text-{size}             /* xs, sm, base, lg, xl, 2xl, 3xl */
.font-{weight}           /* normal, medium, semibold, bold */
.text-{color}            /* gray-{100-900}, blue-600, etc */
.text-center             /* Center text */
.uppercase               /* Uppercase text */
```

### Background & Borders
```css
.bg-{color}              /* Background color */
.rounded-{size}          /* Border radius */
.border                  /* Border */
.shadow-{size}          /* Box shadow */
```

### Interactive States
```css
.hover:bg-{color}        /* Hover background */
.hover:text-{color}      /* Hover text */
.transition              /* Smooth transition */
.cursor-pointer         /* Pointer cursor */
```

---

## 🚀 قواعد عامة للتطوير

1. **الالتزام بالتصميم الموحد**: جميع الصفحات يجب أن تتبع نفس المعايير
2. **استخدام الأيقونات**: كل عنوان أو زر يجب أن يحتوي على أيقونة مناسبة
3. **الألوان**: استخدم palette الألوان المحدد فقط
4. **Responsive**: جميع الصفحات يجب أن تكون responsive
5. **Validation**: جميع الفورمات يجب أن تحتوي على validation
6. **Error Handling**: عرض الأخطاء بشكل واضح وجميل
7. **Empty States**: عرض حالة فارغة مناسبة عند عدم وجود بيانات
8. **Loading States**: عرض loading عند تحميل البيانات
9. **Success Messages**: عرض رسالة نجاح بعد كل عملية ناجحة
10. **Consistency**: الاتساق في التسمية، الألوان، والتصميم

---

## 📌 ملاحظات مهمة

1. **Alpine.js**: استخدم `x-data` للصفحات التي تحتاج interactivity
2. **Tailwind CSS**: استخدم classes الجاهزة قدر الإمكان
3. **Bootstrap**: بعض الصفحات تستخدم Bootstrap (خصوصاً Show pages)
4. **Icons**: استخدم Font Awesome 6.x
5. **Images**: خزن الصور في `storage/app/public` واستخدم `Storage::url()`
6. **Validation**: استخدم Form Requests للـ validation المعقد
7. **Relationships**: حمل العلاقات باستخدام `with()` لتجنب N+1 problem
8. **Pagination**: استخدم `paginate(15)` للقوائم الطويلة
9. **Soft Deletes**: استخدم soft deletes للبيانات المهمة
10. **Authorization**: تأكد من إضافة middleware للحماية

---

## 📚 مراجع إضافية

- **Tailwind CSS**: https://tailwindcss.com/docs
- **Bootstrap 5**: https://getbootstrap.com/docs/5.3
- **Alpine.js**: https://alpinejs.dev
- **Font Awesome**: https://fontawesome.com/icons
- **Laravel Blade**: https://laravel.com/docs/blade

---

**آخر تحديث**: 22 أكتوبر 2025
**الإصدار**: 1.0.0
**المطور**: Huda ERP Development Team

