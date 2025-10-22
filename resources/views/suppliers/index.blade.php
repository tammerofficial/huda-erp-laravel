@extends('layouts.app')

@section('title', 'Suppliers Management')
@section('page-title', 'Suppliers Management')

@section('content')
<div x-data="supplierIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">🏭 Suppliers Management</h2>
                <p class="text-gray-600 mt-1">Manage your supplier network efficiently</p>
            </div>
            <a href="{{ route('suppliers.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                Add New Supplier
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       x-model="searchTerm"
                       @input="filterSuppliers()"
                       placeholder="Search by name, contact person, or email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Type</label>
                <select x-model="selectedType" 
                        @change="filterSuppliers()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="material">Material</option>
                    <option value="service">Service</option>
                    <option value="both">Both</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="selectedStatus" 
                        @change="filterSuppliers()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Supplier
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Credit Limit
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-semibold">
                                        {{ substr($supplier->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $supplier->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $supplier->city ?? 'N/A' }}, {{ $supplier->country ?? 'Kuwait' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $supplier->contact_person ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $supplier->email ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $supplier->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ ucfirst($supplier->supplier_type ?? 'material') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($supplier->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $supplier->credit_limit ? 'KWD ' . number_format($supplier->credit_limit, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('suppliers.show', $supplier) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="deleteSupplier({{ $supplier->id }})"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $supplier->id }}" 
                                      action="{{ route('suppliers.destroy', $supplier) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-industry text-4xl text-gray-300 mb-2"></i>
                                <p>No suppliers found</p>
                                <a href="{{ route('suppliers.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Add your first supplier
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($suppliers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function supplierIndex() {
    return {
        searchTerm: '',
        selectedType: '',
        selectedStatus: '',
        
        filterSuppliers() {
            // This would typically make an AJAX request to filter suppliers
            // For now, we'll just show all suppliers
            console.log('Filtering suppliers...', {
                search: this.searchTerm,
                type: this.selectedType,
                status: this.selectedStatus
            });
        },
        
        deleteSupplier(supplierId) {
            if (confirm('Are you sure you want to delete this supplier?')) {
                document.getElementById(`delete-form-${supplierId}`).submit();
            }
        }
    }
}
</script>
@endsection
