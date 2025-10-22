@extends('layouts.app')

@section('title', 'Product Costs Management')
@section('page-title', 'Product Costs Management')

@section('content')
<div class="container-fluid">
    <!-- 1. Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    🏷️ Product Costs Management
                </h2>
                <p class="text-gray-600 mt-1">Manage and analyze product cost calculations and pricing</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="bulkRecalculate()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-sync mr-2"></i>
                    Bulk Recalculate
                </button>
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>
                    Add Product
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Products</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">With Costs</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['with_costs']) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600">{{ $stats['total_products'] > 0 ? round(($stats['with_costs'] / $stats['total_products']) * 100, 1) : 0 }}%</span> coverage
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Without Costs</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['without_costs']) }}</h3>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Average Cost</p>
                    <h3 class="text-2xl font-bold text-gray-900">${{ number_format($stats['average_cost'], 2) }}</h3>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fas fa-dollar-sign text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <form method="GET" action="{{ route('cost-management.products') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Product name or SKU..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cost Range</label>
                <select name="cost_range" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Products</option>
                    <option value="no_cost" {{ request('cost_range') === 'no_cost' ? 'selected' : '' }}>No Cost</option>
                    <option value="low_cost" {{ request('cost_range') === 'low_cost' ? 'selected' : '' }}>Low Cost (< $50)</option>
                    <option value="medium_cost" {{ request('cost_range') === 'medium_cost' ? 'selected' : '' }}>Medium Cost ($50-$200)</option>
                    <option value="high_cost" {{ request('cost_range') === 'high_cost' ? 'selected' : '' }}>High Cost (> $200)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Has BOM</label>
                <select name="has_bom" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Products</option>
                    <option value="yes" {{ request('has_bom') === 'yes' ? 'selected' : '' }}>With BOM</option>
                    <option value="no" {{ request('has_bom') === 'no' ? 'selected' : '' }}>Without BOM</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- 4. Main Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BOM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($product->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->category ?? 'No Category' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->sku }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->cost)
                                <span class="text-green-600 font-semibold">${{ number_format($product->cost, 2) }}</span>
                            @else
                                <span class="text-red-600 font-semibold">No Cost</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($product->price ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->cost && $product->price)
                                @php
                                    $margin = (($product->price - $product->cost) / $product->price) * 100;
                                @endphp
                                <span class="text-sm font-semibold {{ $margin >= 30 ? 'text-green-600' : ($margin >= 15 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($margin, 1) }}%
                                </span>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->billOfMaterials->count() > 0)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $product->billOfMaterials->count() }} BOM{{ $product->billOfMaterials->count() > 1 ? 's' : '' }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    No BOM
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="recalculateCost({{ $product->id }})" 
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Recalculate Cost">
                                    <i class="fas fa-sync"></i>
                                </button>
                                <a href="{{ route('products.show', $product) }}" 
                                   class="text-green-600 hover:text-green-900"
                                   title="View Product">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" 
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-2"></i>
                                <p class="text-lg">No products found</p>
                                <a href="{{ route('products.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Add your first product
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- 5. Pagination -->
        @if($products->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// Recalculate Cost Function
function recalculateCost(productId) {
    if (confirm('Are you sure you want to recalculate the cost for this product?')) {
        fetch(`/cost-management/products/${productId}/recalculate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Cost recalculated successfully! New cost: $${data.new_cost}`);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while recalculating the cost.');
        });
    }
}

// Bulk Recalculate Function
function bulkRecalculate() {
    if (confirm('Are you sure you want to recalculate costs for all products? This may take a few minutes.')) {
        fetch('{{ route("cost-management.products.bulk-recalculate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Successfully updated ${data.updated} products!`);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while recalculating costs.');
        });
    }
}
</script>
@endsection