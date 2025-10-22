@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div x-data="productForm()">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">✏️ Edit Product: {{ $product->name }}</h2>
                    <p class="text-gray-600 mt-1">Update product information</p>
                </div>
                <a href="{{ route('products.index') }}" 
                   class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Back to Products
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Basic Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               value="{{ old('name', $product->name) }}"
                               placeholder="Enter product name"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                            SKU <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="sku" id="sku" required
                               value="{{ old('sku', $product->sku) }}"
                               placeholder="Enter product SKU"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('sku') border-red-500 @enderror">
                        @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category
                        </label>
                        <input type="text" name="category" id="category"
                               value="{{ old('category', $product->category) }}"
                               placeholder="Enter product category"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('category') border-red-500 @enderror">
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="product_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Type <span class="text-red-500">*</span>
                        </label>
                        <select name="product_type" id="product_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('product_type') border-red-500 @enderror">
                            <option value="">Select Product Type</option>
                            <option value="standard" {{ old('product_type', $product->product_type) == 'standard' ? 'selected' : '' }}>Standard</option>
                            <option value="custom" {{ old('product_type', $product->product_type) == 'custom' ? 'selected' : '' }}>Custom</option>
                            <option value="service" {{ old('product_type', $product->product_type) == 'service' ? 'selected' : '' }}>Service</option>
                        </select>
                        @error('product_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                    Pricing Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Price (KWD) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" id="price" step="0.01" min="0" required
                               value="{{ old('price', $product->price) }}"
                               placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Cost (KWD)
                        </label>
                        <input type="number" name="cost" id="cost" step="0.01" min="0"
                               value="{{ old('cost', $product->cost) }}"
                               placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('cost') border-red-500 @enderror">
                        @error('cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Inventory Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-warehouse mr-2 text-orange-600"></i>
                    Inventory Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Stock Quantity
                        </label>
                        <input type="number" name="stock_quantity" id="stock_quantity" min="0"
                               value="{{ old('stock_quantity', $product->stock_quantity) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('stock_quantity') border-red-500 @enderror">
                        @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reorder_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Reorder Level
                        </label>
                        <input type="number" name="reorder_level" id="reorder_level" min="0"
                               value="{{ old('reorder_level', $product->reorder_level) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('reorder_level') border-red-500 @enderror">
                        @error('reorder_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                            Unit
                        </label>
                        <input type="text" name="unit" id="unit"
                               value="{{ old('unit', $product->unit) }}"
                               placeholder="e.g., piece, kg, liter"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('unit') border-red-500 @enderror">
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                            Weight (kg)
                        </label>
                        <input type="number" name="weight" id="weight" step="0.01" min="0"
                               value="{{ old('weight', $product->weight) }}"
                               placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('weight') border-red-500 @enderror">
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                    Product Details
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  placeholder="Enter product description..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($product->image_url)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" 
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            <div class="text-sm text-gray-500">
                                <p>Current product image</p>
                                <p class="text-xs">Upload a new image to replace it</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Image
                        </label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('image') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Upload a new product image (JPG, PNG, GIF)</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-gray-900 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active Product
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex justify-end space-x-4 space-x-reverse">
                    <a href="{{ route('products.index') }}" 
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 btn-primary transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function productForm() {
    return {
        init() {
            // Initialize any form-specific functionality
        }
    }
}
</script>
@endsection