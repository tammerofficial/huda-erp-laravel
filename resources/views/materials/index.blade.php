@extends('layouts.app')

@section('title', 'Materials Management')
@section('page-title', 'Materials Management')

@section('content')
<div x-data="materialIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">🧱 Materials Management</h2>
                <p class="text-gray-600 mt-1">Manage your material inventory efficiently</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('materials.low-stock') }}" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Low Stock Alert
                </a>
                <a href="{{ route('materials.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Material
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       x-model="searchTerm"
                       @input="filterMaterials()"
                       placeholder="Search by name, SKU, or description..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select x-model="selectedCategory" 
                        @change="filterMaterials()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    <option value="raw_materials">Raw Materials</option>
                    <option value="components">Components</option>
                    <option value="tools">Tools</option>
                    <option value="packaging">Packaging</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="selectedStatus" 
                        @change="filterMaterials()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="discontinued">Discontinued</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Materials Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($materials as $material)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($material->image_url)
                                        <img class="h-12 w-12 rounded-lg object-cover" src="{{ $material->image_url }}" alt="{{ $material->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-cube text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($material->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $material->sku ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst($material->category ?? 'N/A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $material->color ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $material->size ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            KWD {{ number_format($material->unit_cost ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $material->supplier->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $totalStock = $material->available_quantity;
                                $isLowStock = $material->isLowStock();
                            @endphp
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold {{ $isLowStock ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $totalStock }} {{ $material->unit }}
                                </span>
                                @if($isLowStock)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle"></i> Low
                                    </span>
                                @endif
                            </div>
                            @if($material->reorder_level)
                                <div class="text-xs text-gray-500 mt-1">
                                    Min: {{ $material->min_stock_level ?? $material->reorder_level }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($material->is_active)
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
                                <a href="{{ route('materials.show', $material) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('materials.edit', $material) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="deleteMaterial({{ $material->id }})"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $material->id }}" 
                                      action="{{ route('materials.destroy', $material) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cube text-4xl text-gray-300 mb-2"></i>
                                <p>No materials found</p>
                                <a href="{{ route('materials.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Add your first material
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($materials->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $materials->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function materialIndex() {
    return {
        searchTerm: '',
        selectedCategory: '',
        selectedStatus: '',
        
        filterMaterials() {
            // This would typically make an AJAX request to filter materials
            // For now, we'll just show all materials
            console.log('Filtering materials...', {
                search: this.searchTerm,
                category: this.selectedCategory,
                status: this.selectedStatus
            });
        },
        
        deleteMaterial(materialId) {
            if (confirm('Are you sure you want to delete this material?')) {
                document.getElementById(`delete-form-${materialId}`).submit();
            }
        }
    }
}
</script>
@endsection