@extends('layouts.app')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
<div x-data="customerShow()">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-green-500 flex items-center justify-center text-white text-2xl font-bold mr-4">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h2>
                        <p class="text-gray-600">{{ $customer->email }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->customer_type ?? 'Individual' }} Customer</p>
                    </div>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('customers.edit', $customer) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Customer
                    </a>
                    <a href="{{ route('customers.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Back to Customers
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                            <p class="text-gray-900">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-900">{{ $customer->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                            <p class="text-gray-900">{{ $customer->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Customer Type</label>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($customer->customer_type ?? 'individual') }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            @if($customer->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Credit Limit</label>
                            <p class="text-gray-900">{{ $customer->credit_limit ? 'KWD ' . number_format($customer->credit_limit, 2) : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>
                        Address Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">City</label>
                            <p class="text-gray-900">{{ $customer->city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Country</label>
                            <p class="text-gray-900">{{ $customer->country ?? 'Kuwait' }}</p>
                        </div>
                    </div>
                    
                    @if($customer->address)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Full Address</label>
                        <p class="text-gray-900">{{ $customer->address }}</p>
                    </div>
                    @endif
                </div>

                <!-- Orders History -->
                @if($customer->orders && $customer->orders->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-shopping-cart mr-2 text-purple-600"></i>
                        Recent Orders
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($customer->orders->take(5) as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">KWD {{ number_format($order->total_amount ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-bolt mr-2 text-yellow-600"></i>
                        Quick Actions
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('customers.edit', $customer) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Customer
                        </a>
                        
                        <a href="{{ route('orders.create') }}?customer_id={{ $customer->id }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            New Order
                        </a>
                        
                        <button @click="deleteCustomer({{ $customer->id }})"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Customer
                        </button>
                        
                        <form id="delete-form-{{ $customer->id }}" 
                              action="{{ route('customers.destroy', $customer) }}" 
                              method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
                        Statistics
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Orders</span>
                            <span class="font-semibold text-gray-900">{{ $customer->orders->count() ?? 0 }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="font-semibold {{ $customer->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Customer Type</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($customer->customer_type ?? 'individual') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Credit Limit</span>
                            <span class="font-semibold text-gray-900">{{ $customer->credit_limit ? 'KWD ' . number_format($customer->credit_limit, 2) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-phone mr-2 text-green-600"></i>
                        Contact Information
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-3"></i>
                            <span class="text-sm text-gray-900">{{ $customer->email }}</span>
                        </div>
                        
                        @if($customer->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-3"></i>
                            <span class="text-sm text-gray-900">{{ $customer->phone }}</span>
                        </div>
                        @endif
                        
                        @if($customer->address)
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-3 mt-1"></i>
                            <span class="text-sm text-gray-900">{{ $customer->address }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function customerShow() {
    return {
        deleteCustomer(customerId) {
            if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
                document.getElementById(`delete-form-${customerId}`).submit();
            }
        }
    }
}
</script>
@endsection
