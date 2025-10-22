@extends('layouts.app')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('content')
<div x-data="productDetails()">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">📦 Product Details: {{ $product->name }}</h2>
                    <p class="text-gray-600 mt-1">Complete product information and specifications</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('products.edit', $product) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Product Image -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-image mr-2 text-blue-600"></i>
                    Product Image
                </h3>
                
                @if($product->image_url)
                    <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" 
                         class="w-full h-64 object-cover rounded-lg border border-gray-300">
                @else
                    <div class="w-full h-64 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p>No image available</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Basic Information -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-green-600"></i>
                    Basic Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <p class="text-gray-900 font-medium">{{ $product->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <p class="text-gray-900 font-mono">{{ $product->sku }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <p class="text-gray-900">{{ $product->category ?? 'Not specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($product->product_type) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                            @if($product->is_active) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created Date</label>
                        <p class="text-gray-900">{{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Inventory -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Pricing Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                    Pricing Information
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Price:</span>
                        <span class="text-xl font-bold text-green-600 font-mono">{{ number_format($product->price, 2) }} KWD</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Cost:</span>
                        <span class="text-gray-900 font-mono">{{ $product->cost ? number_format($product->cost, 2) . ' KWD' : 'Not specified' }}</span>
                    </div>
                    
                    @if($product->cost && $product->price)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Profit Margin:</span>
                        <span class="text-gray-900 font-mono">{{ number_format((($product->price - $product->cost) / $product->price) * 100, 1) }}%</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Inventory Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-warehouse mr-2 text-orange-600"></i>
                    Inventory Information
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Stock Quantity:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($product->stock_quantity <= $product->reorder_level) bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $product->stock_quantity }} {{ $product->unit }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Reorder Level:</span>
                        <span class="text-gray-900">{{ $product->reorder_level }} {{ $product->unit }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Unit:</span>
                        <span class="text-gray-900">{{ $product->unit }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Weight:</span>
                        <span class="text-gray-900">{{ $product->weight ? $product->weight . ' kg' : 'Not specified' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        @if($product->description)
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                Product Description
            </h3>
            <div class="prose max-w-none">
                <p class="text-gray-900 leading-relaxed">{{ $product->description }}</p>
            </div>
        </div>
        @endif

        <!-- Bill of Materials Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list mr-2 text-indigo-600"></i>
                    Bill of Materials (BOM)
                </h3>
                <a href="{{ route('products.bom.create', $product) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add BOM
                </a>
            </div>
            
            @if($product->billOfMaterials->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Default</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($product->billOfMaterials as $bom)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bom->version }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                        @if($bom->status == 'active') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($bom->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                    {{ $bom->total_cost ? number_format($bom->total_cost, 2) . ' KWD' : 'Not calculated' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($bom->is_default)
                                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            Default
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bom->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-list text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No Bill of Materials found for this product</p>
                    <p class="text-gray-400 text-sm mt-2">Click "Add BOM" to create the first bill of materials</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function productDetails() {
    return {
        init() {
            // Initialize any functionality needed for product details
        }
    }
}
</script>
@endsection