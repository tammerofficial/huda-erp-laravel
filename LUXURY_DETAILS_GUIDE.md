# 🎨 Luxury Details Pages - Design Guide

## Overview
This guide provides a standardized approach to creating consistent, luxurious detail pages across the Huda ERP system.

---

## 🏗️ Page Structure

All detail/show pages should follow this structure:

```
1. Header Card (with gradient background)
2. Left Column (4/12) - Quick Stats & Info
3. Right Column (8/12) - Detailed Information & Tables
```

---

## 📦 Available Components

### 1. Luxury Details Header
Location: `resources/views/components/luxury-details-header.blade.php`

```blade
@include('components.luxury-details-header', [
    'title' => 'Item Name',
    'subtitle' => 'Additional info',
    'icon' => 'fas fa-warehouse',
    'badge' => ['text' => 'Active', 'class' => 'bg-success'],
    'editRoute' => route('items.edit', $item),
    'backRoute' => route('items.index')
])
```

### 2. Info Item
Location: `resources/views/components/info-item.blade.php`

```blade
@include('components.info-item', [
    'label' => 'Field Name',
    'value' => $item->field_name,
    'icon' => 'fas fa-icon' // optional
])
```

---

## 🎨 Standard Color Palette

- **Primary Gold**: `#d4af37`
- **Dark Background**: `#1a1a1a` to `#2d2d2d` (gradient)
- **White Text on Dark**: `#ffffff` with `opacity: 0.9`
- **Info Background**: `#fafafa` (hover: `#f5f5f5`)
- **Table Header**: `#f8f8f8`

---

## 📐 Standard Layout Template

```blade
@extends('layouts.app')

@section('title', 'Item Details')
@section('page-title', 'Item Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    @include('components.luxury-details-header', [
        'title' => $item->name,
        'subtitle' => $item->description,
        'icon' => 'fas fa-cube',
        'badge' => ['text' => $item->is_active ? 'Active' : 'Inactive', 'class' => $item->is_active ? 'bg-success' : 'bg-danger'],
        'editRoute' => route('items.edit', $item),
        'backRoute' => route('items.index')
    ])

    <div class="row g-4">
        <!-- Left Column - Stats & Quick Info -->
        <div class="col-lg-4">
            <!-- Quick Stats Card -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-chart-bar"></i> Quick Stats</h5>
                </div>
                <div class="p-4">
                    <!-- Add stats here -->
                </div>
            </div>

            <!-- Info Card -->
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-info-circle"></i> Information</h5>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        @include('components.info-item', ['label' => 'Name', 'value' => $item->name])
                    </div>
                    <!-- Add more info items -->
                </div>
            </div>
        </div>

        <!-- Right Column - Details -->
        <div class="col-lg-8">
            <!-- Detailed Information Card -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-list"></i> Details</h5>
                </div>
                <div class="p-4">
                    <!-- Add tables or detailed info here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 🔖 Badge Colors

```php
// Status badges
'bg-success'  // Active, In Stock, Completed
'bg-warning'  // Low Stock, Pending
'bg-danger'   // Inactive, Out of Stock, Cancelled
'bg-info'     // Transfer, Processing
'bg-secondary' // Default, SKU, Tags
'bg-primary'  // Counts, Numbers
```

---

## 📊 Tables

### Standard Table Style
```blade
<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead style="background-color: #f8f8f8;">
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

### Empty State
```blade
<div class="text-center py-5">
    <i class="fas fa-box-open fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
    <p class="text-muted">No items found</p>
    <a href="#" class="btn btn-sm btn-outline-secondary mt-2">
        <i class="fas fa-plus"></i> Add Item
    </a>
</div>
```

---

## 💡 Best Practices

### 1. Icons
Use Font Awesome icons consistently:
- `fas fa-warehouse` - Warehouses
- `fas fa-cube` - Materials
- `fas fa-box` - Products
- `fas fa-shopping-cart` - Orders
- `fas fa-users` - Customers
- `fas fa-truck` - Suppliers
- `fas fa-user-tie` - Employees
- `fas fa-money-bill` - Accounting

### 2. Hover Effects
All clickable elements should have hover effects:
```css
.info-item:hover {
    transform: translateY(-2px);
}
```

### 3. Links
```blade
<a href="{{ route('items.show', $item) }}" 
   style="color: #1a1a1a; text-decoration: none; font-weight: 600;">
    {{ $item->name }}
</a>
```

Gold links for special emphasis:
```blade
<a href="{{ route('items.show', $item) }}" 
   style="color: #d4af37; text-decoration: none;">
    {{ $item->name }}
</a>
```

---

## 📝 Pages to Update

Apply this design to:
- [x] Materials → `resources/views/materials/show.blade.php`
- [x] Warehouses → `resources/views/warehouses/show.blade.php`
- [ ] Products → `resources/views/products/show.blade.php`
- [ ] Orders → `resources/views/orders/show.blade.php`
- [ ] Customers → `resources/views/customers/show.blade.php`
- [ ] Suppliers → `resources/views/suppliers/show.blade.php`
- [ ] Employees → `resources/views/employees/show.blade.php`
- [ ] Production Orders → `resources/views/production/show.blade.php`
- [ ] Purchase Orders → `resources/views/purchases/show.blade.php`
- [ ] Invoices → `resources/views/invoices/show.blade.php`

---

## 🚀 Quick Start Example

Create a new show page:

1. Copy the standard template above
2. Replace `$item` with your model variable
3. Customize the icon and colors
4. Add your specific stats and information
5. Test and ensure consistency

---

**Last Updated**: October 2025  
**Version**: 1.0  
**System**: Huda ERP - Haute Couture Management System

