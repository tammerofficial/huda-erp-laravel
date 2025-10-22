@extends('layouts.app')

@section('title', 'Warehouses Management')
@section('page-title', 'Warehouses Management')

@section('content')
<div x-data="warehouseIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">🏢 Warehouses Management</h2>
                <p class="text-gray-600 mt-1">Manage warehouse locations and inventory storage</p>
            </div>
            <a href="{{ route('warehouses.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                Add New Warehouse
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
                       @input="filterWarehouses()"
                       placeholder="Search by name or location..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="selectedStatus" 
                        @change="filterWarehouses()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Manager</label>
                <select x-model="selectedManager" 
                        @change="filterWarehouses()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Managers</option>
                    <option value="assigned">Assigned</option>
                    <option value="unassigned">Unassigned</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Warehouses Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manager</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($warehouses as $warehouse)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                    {{ substr($warehouse->name, 0, 2) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $warehouse->name }}</div>
                                    <div class="text-sm text-gray-500">#{{ $warehouse->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $warehouse->location ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $warehouse->capacity ?? 'Unlimited' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $warehouse->manager ? $warehouse->manager->user->name : 'Not Assigned' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($warehouse->is_active)
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
                                <a href="{{ route('warehouses.show', $warehouse) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('warehouses.edit', $warehouse) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('warehouses.inventory', $warehouse) }}" 
                                   class="text-green-600 hover:text-green-900" title="Inventory">
                                    <i class="fas fa-boxes"></i>
                                </a>
                                <a href="{{ route('warehouses.movements', $warehouse) }}" 
                                   class="text-purple-600 hover:text-purple-900" title="Movements">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                                <button @click="deleteWarehouse({{ $warehouse->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $warehouse->id }}" 
                                      action="{{ route('warehouses.destroy', $warehouse) }}" 
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
                                <i class="fas fa-warehouse text-4xl text-gray-300 mb-2"></i>
                                <p>No warehouses found</p>
                                <a href="{{ route('warehouses.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Add your first warehouse
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($warehouses->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $warehouses->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function warehouseIndex() {
    return {
        searchTerm: '',
        selectedStatus: '',
        selectedManager: '',
        
        filterWarehouses() {
            console.log('Filtering warehouses...', {
                search: this.searchTerm,
                status: this.selectedStatus,
                manager: this.selectedManager
            });
        },
        
        deleteWarehouse(warehouseId) {
            if (confirm('Are you sure you want to delete this warehouse?')) {
                document.getElementById(`delete-form-${warehouseId}`).submit();
            }
        }
    }
}
</script>
@endsection
