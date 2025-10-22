@extends('layouts.app')

@section('title', 'Customers Management')
@section('page-title', 'Customers Management')

@section('content')
<div x-data="customerIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">👥 Customers Management</h2>
                <p class="text-gray-600 mt-1">Manage your customer database efficiently</p>
            </div>
            <a href="{{ route('customers.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                Add New Customer
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
                       @input="filterCustomers()"
                       placeholder="Search by name, email, or phone..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                <select x-model="selectedType" 
                        @change="filterCustomers()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="individual">Individual</option>
                    <option value="business">Business</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="selectedStatus" 
                        @change="filterCustomers()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
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
                            Orders
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center text-white font-semibold">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $customer->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $customer->city ?? 'N/A' }}, {{ $customer->country ?? 'Kuwait' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                            <div class="text-sm text-gray-500">{{ $customer->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($customer->customer_type ?? 'individual') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($customer->is_active)
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
                            {{ $customer->orders_count ?? 0 }} orders
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('customers.show', $customer) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="deleteCustomer({{ $customer->id }})"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $customer->id }}" 
                                      action="{{ route('customers.destroy', $customer) }}" 
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
                                <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                                <p>No customers found</p>
                                <a href="{{ route('customers.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Add your first customer
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function customerIndex() {
    return {
        searchTerm: '',
        selectedType: '',
        selectedStatus: '',
        
        filterCustomers() {
            // This would typically make an AJAX request to filter customers
            // For now, we'll just show all customers
            console.log('Filtering customers...', {
                search: this.searchTerm,
                type: this.selectedType,
                status: this.selectedStatus
            });
        },
        
        deleteCustomer(customerId) {
            if (confirm('Are you sure you want to delete this customer?')) {
                document.getElementById(`delete-form-${customerId}`).submit();
            }
        }
    }
}
</script>
@endsection
