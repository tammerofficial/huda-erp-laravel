# 🚀 Quick Reference - نماذج سريعة للصفحات

## 📋 Index Page - نموذج كامل

```blade
@extends('layouts.app')

@section('title', 'Resources Management')
@section('page-title', 'Resources Management')

@section('content')
<div x-data="resourceIndex()">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">📦 Resources Management</h2>
                <p class="text-gray-600 mt-1">Manage all your resources efficiently</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('resources.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Resource
                </a>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       x-model="searchTerm"
                       placeholder="Search by name or code..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select x-model="selectedCategory"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    <option value="category1">Category 1</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="selectedStatus"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($resources as $resource)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">{{ $resource->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $resource->code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($resource->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('resources.show', $resource) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('resources.edit', $resource) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="deleteResource({{ $resource->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-2"></i>
                                <p class="text-lg">No resources found</p>
                                <a href="{{ route('resources.create') }}" class="mt-2 text-blue-600 hover:text-blue-800">
                                    Add your first resource
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($resources->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $resources->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function resourceIndex() {
    return {
        searchTerm: '',
        selectedCategory: '',
        selectedStatus: '',
        
        deleteResource(id) {
            if (confirm('Are you sure you want to delete this resource?')) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        }
    }
}
</script>
@endsection
```

---

## ➕ Create Page - نموذج كامل

```blade
@extends('layouts.app')

@section('title', 'Create Resource')
@section('page-title', 'Create Resource')

@section('content')
<div class="container-fluid">
    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">➕ Create New Resource</h2>
                    <p class="text-gray-600 mt-1">Fill in the information below</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('resources.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Resource Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           required
                           placeholder="Enter resource name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           value="{{ old('code') }}"
                           required
                           placeholder="RES-001"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        <option value="category1" {{ old('category') == 'category1' ? 'selected' : '' }}>Category 1</option>
                        <option value="category2" {{ old('category') == 'category2' ? 'selected' : '' }}>Category 2</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
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

                <!-- Description (Full Width) -->
                <div class="md:col-span-2">
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

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Image
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

                <!-- Active Status -->
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
            </div>
        </div>

    </form>
</div>
@endsection
```

---

## ✏️ Edit Page - نموذج كامل

```blade
@extends('layouts.app')

@section('title', 'Edit Resource')
@section('page-title', 'Edit Resource')

@section('content')
<div class="container-fluid">
    <form action="{{ route('resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">✏️ Edit Resource: {{ $resource->name }}</h2>
                    <p class="text-gray-600 mt-1">Update information and specifications</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('resources.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Resource Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $resource->name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           value="{{ old('code', $resource->code) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        <option value="category1" {{ old('category', $resource->category) == 'category1' ? 'selected' : '' }}>Category 1</option>
                        <option value="category2" {{ old('category', $resource->category) == 'category2' ? 'selected' : '' }}>Category 2</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="quantity" 
                           value="{{ old('quantity', $resource->quantity) }}"
                           min="0"
                           step="0.01"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity') border-red-500 @enderror">
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $resource->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                @if($resource->image_url)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <img src="{{ Storage::url($resource->image_url) }}" alt="{{ $resource->name }}" class="h-32 w-32 object-cover rounded-lg">
                </div>
                @endif

                <!-- New Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Update Image
                    </label>
                    <input type="file" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $resource->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active Status
                    </label>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
```

---

## 👁️ Show Page - نموذج كامل

```blade
@extends('layouts.app')

@section('title', 'Resource Details')
@section('page-title', 'Resource Details')

@section('content')
<div class="container-fluid">
    
    <!-- Header Card -->
    <div class="luxury-card mb-4">
        <div class="p-4 border-bottom" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1" style="color: #d4af37; font-weight: 600;">
                        <i class="fas fa-cube"></i> {{ $resource->name }}
                    </h3>
                    <p class="mb-0" style="color: #ffffff; opacity: 0.9;">
                        <span class="badge bg-secondary">{{ $resource->code }}</span>
                        @if($resource->is_active)
                            <span class="badge bg-success ms-2">Active</span>
                        @else
                            <span class="badge bg-danger ms-2">Inactive</span>
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('resources.edit', $resource) }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('resources.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-4">
            <!-- Image Card -->
            <div class="luxury-card mb-4">
                <div class="p-4">
                    @if($resource->image_url)
                        <img src="{{ Storage::url($resource->image_url) }}" 
                             alt="{{ $resource->name }}" 
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
                    <h5 class="section-title mb-0"><i class="fas fa-chart-bar"></i> Quick Stats</h5>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Quantity</span>
                            <strong style="color: #d4af37; font-size: 1.2rem;">{{ $resource->quantity }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Category</span>
                            <strong>{{ ucfirst($resource->category) }}</strong>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Status</span>
                            <strong class="badge bg-{{ $resource->is_active ? 'success' : 'danger' }}">
                                {{ $resource->is_active ? 'Active' : 'Inactive' }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-info-circle"></i> Basic Information</h5>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Resource Name</label>
                                <p class="mb-0 fw-semibold">{{ $resource->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Code</label>
                                <p class="mb-0 fw-semibold">{{ $resource->code }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Category</label>
                                <p class="mb-0 fw-semibold">{{ ucfirst($resource->category) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Quantity</label>
                                <p class="mb-0 fw-semibold">{{ $resource->quantity }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info-item">
                                <label class="text-muted mb-1">Description</label>
                                <p class="mb-0">{{ $resource->description ?? 'No description available' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Created At</label>
                                <p class="mb-0 text-muted">{{ $resource->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Last Updated</label>
                                <p class="mb-0 text-muted">{{ $resource->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
}

.info-item p {
    font-size: 1rem;
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

---

## 📝 تاريخ آخر تحديث

**التاريخ**: 22 أكتوبر 2025  
**الوقت**: الآن  
**الحالة**: ✅ جاهز للاستخدام

---

**ملاحظة**: هذه النماذج هي نقطة البداية. يمكن تعديلها حسب احتياجات كل صفحة، ولكن يجب الالتزام بالتصميم والألوان المحددة في ملف `DESIGN_STANDARDS.md`.

