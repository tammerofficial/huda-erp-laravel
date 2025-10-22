@extends('layouts.app')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
<div x-data="customerShow()">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                    <div class="mr-6">
                        <h2 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h2>
                        <p class="text-gray-600 flex items-center mt-1">
                            <i class="fas fa-envelope mr-2 text-sm"></i>
                            {{ $customer->email }}
                        </p>
                        <div class="flex items-center mt-2 space-x-3">
                            <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-gray-900 text-yellow-400">
                                <i class="fas fa-user-tag mr-1"></i>
                                {{ ucfirst($customer->customer_type ?? 'individual') }}
                            </span>
                            @if($customer->is_active)
                                <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactive
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                Member since {{ $customer->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Customer
                    </a>
                    <a href="{{ route('customers.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            @php
                $totalOrders = $customer->orders->count();
                $totalSpent = $customer->orders->sum('final_amount');
                $pendingOrders = $customer->orders->where('status', 'pending')->count();
                $completedOrders = $customer->orders->where('status', 'completed')->count();
            @endphp
            
            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalOrders }}</p>
                        <p class="text-xs text-gray-500 mt-1">All time orders</p>
                    </div>
                    <div class="h-14 w-14 rounded-full bg-gray-900 flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-2xl text-yellow-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Spent</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($totalSpent, 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">KWD</p>
                    </div>
                    <div class="h-14 w-14 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Pending Orders</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingOrders }}</p>
                        <p class="text-xs text-gray-500 mt-1">Awaiting processing</p>
                    </div>
                    <div class="h-14 w-14 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Completed</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $completedOrders }}</p>
                        <p class="text-xs text-gray-500 mt-1">Successfully delivered</p>
                    </div>
                    <div class="h-14 w-14 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Orders History -->
                <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-shopping-bag mr-3 text-yellow-500"></i>
                            Orders History
                            <span class="mr-2 px-3 py-1 text-sm bg-gray-900 text-yellow-400 rounded-full">
                                {{ $totalOrders }} Orders
                            </span>
                        </h3>
                    </div>
                    
                    @if($customer->orders && $customer->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($customer->orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gray-900 flex items-center justify-center text-yellow-400 font-semibold">
                                                #{{ $order->id }}
                                            </div>
                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                                @if($order->notes)
                                                <div class="text-xs text-gray-500" title="{{ $order->notes }}">
                                                    <i class="fas fa-sticky-note mr-1"></i>
                                                    {{ Str::limit($order->notes, 30) }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->order_date ? $order->order_date->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 py-1 bg-gray-100 rounded-full">
                                            {{ $order->items->count() ?? 0 }} items
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">KWD {{ number_format($order->final_amount ?? 0, 3) }}</div>
                                        @if($order->discount_amount > 0)
                                        <div class="text-xs text-green-600">
                                            <i class="fas fa-tag"></i> -{{ number_format($order->discount_amount, 3) }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-gray-900 text-yellow-400',
                                                'in_production' => 'bg-purple-100 text-purple-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $paymentColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'paid' => 'bg-green-100 text-green-800',
                                                'partial' => 'bg-gray-800 text-yellow-300',
                                                'overdue' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentColors[$order->payment_status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($order->payment_status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="text-gray-900 hover:text-yellow-600" title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-12 text-center">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg mb-4">No orders yet</p>
                        <a href="{{ route('orders.create') }}?customer_id={{ $customer->id }}" class="btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                            Create First Order
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Order Notes & Comments -->
                @if($customer->orders && $customer->orders->whereNotNull('notes')->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-comments mr-3 text-yellow-600"></i>
                        Order Notes & Comments
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($customer->orders->whereNotNull('notes') as $order)
                        <div class="border-r-4 border-yellow-400 bg-yellow-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-900">Order #{{ $order->id }}</span>
                                    <span class="mr-2 text-gray-400">•</span>
                                    <span class="text-sm text-gray-600">{{ $order->order_date->format('M d, Y') }}</span>
                                </div>
                                <span class="px-2 py-1 text-xs bg-yellow-200 text-yellow-800 rounded-full">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <p class="text-gray-700 text-sm">{{ $order->notes }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-id-card mr-3 text-green-600"></i>
                        Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Full Name</label>
                            <p class="text-gray-900 font-medium">{{ $customer->name }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Email Address</label>
                            <p class="text-gray-900 font-medium">{{ $customer->email }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Phone Number</label>
                            <p class="text-gray-900 font-medium">{{ $customer->phone ?? 'Not Provided' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Customer Type</label>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-900 text-yellow-400">
                                {{ ucfirst($customer->customer_type ?? 'individual') }}
                            </span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Credit Limit</label>
                            <p class="text-gray-900 font-medium">{{ $customer->credit_limit ? 'KWD ' . number_format($customer->credit_limit, 3) : 'No Limit' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Member Since</label>
                            <p class="text-gray-900 font-medium">{{ $customer->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt mr-3 text-red-600"></i>
                        Address Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">City</label>
                            <p class="text-gray-900 font-medium">{{ $customer->city ?? 'Not Provided' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Country</label>
                            <p class="text-gray-900 font-medium">{{ $customer->country ?? 'Kuwait' }}</p>
                        </div>
                    </div>
                    
                    @if($customer->address)
                    <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <label class="block text-xs font-semibold text-gray-900 uppercase mb-2">
                            <i class="fas fa-map mr-1"></i>
                            Full Address
                        </label>
                        <p class="text-gray-900 font-medium">{{ $customer->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Financial Summary -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                        Financial Summary
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-sm text-gray-600">Total Spent</span>
                            <span class="font-bold text-green-600 text-lg">KWD {{ number_format($totalSpent, 3) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-sm text-gray-600">Average Order Value</span>
                            <span class="font-bold text-gray-900">KWD {{ $totalOrders > 0 ? number_format($totalSpent / $totalOrders, 3) : '0.000' }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-sm text-gray-600">Credit Limit</span>
                            <span class="font-bold text-yellow-600">{{ $customer->credit_limit ? 'KWD ' . number_format($customer->credit_limit, 3) : 'No Limit' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Available Credit</span>
                            <span class="font-bold text-purple-600">
                                {{ $customer->credit_limit ? 'KWD ' . number_format(max(0, $customer->credit_limit - $totalSpent), 3) : 'Unlimited' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Status Breakdown -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-purple-600"></i>
                        Order Breakdown
                    </h3>
                    
                    <div class="space-y-3">
                        @php
                            $statusCounts = [
                                'pending' => $customer->orders->where('status', 'pending')->count(),
                                'confirmed' => $customer->orders->where('status', 'confirmed')->count(),
                                'in_production' => $customer->orders->where('status', 'in_production')->count(),
                                'completed' => $customer->orders->where('status', 'completed')->count(),
                                'cancelled' => $customer->orders->where('status', 'cancelled')->count(),
                            ];
                        @endphp
                        
                        @foreach($statusCounts as $status => $count)
                        @if($count > 0)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                @php
                                    $statusIcons = [
                                        'pending' => 'clock',
                                        'confirmed' => 'check',
                                        'in_production' => 'cog',
                                        'completed' => 'check-circle',
                                        'cancelled' => 'times-circle'
                                    ];
                                    $statusColors = [
                                        'pending' => 'text-yellow-600',
                                        'confirmed' => 'text-gray-900',
                                        'in_production' => 'text-purple-600',
                                        'completed' => 'text-green-600',
                                        'cancelled' => 'text-red-600'
                                    ];
                                @endphp
                                <i class="fas fa-{{ $statusIcons[$status] }} mr-3 {{ $statusColors[$status] }}"></i>
                                <span class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ $count }}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-address-card mr-2 text-indigo-600"></i>
                        Contact Details
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-envelope text-yellow-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                                <p class="text-sm text-gray-900 font-medium break-all">{{ $customer->email }}</p>
                            </div>
                        </div>
                        
                        @if($customer->phone)
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-phone text-green-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Phone</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $customer->phone }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($customer->address)
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-map-marker-alt text-red-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Address</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $customer->address }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-history mr-2 text-gray-600"></i>
                        Recent Activity
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-user-plus text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900 font-medium">Customer Created</p>
                                <p class="text-xs text-gray-500">{{ $customer->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if($customer->orders->count() > 0)
                        <div class="flex items-start">
                            <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-shopping-cart text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900 font-medium">Last Order</p>
                                <p class="text-xs text-gray-500">{{ $customer->orders->sortByDesc('created_at')->first()->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start">
                            <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-edit text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900 font-medium">Last Updated</p>
                                <p class="text-xs text-gray-500">{{ $customer->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function customerShow() {
    return {
        // Component functionality
    }
}
</script>
@endsection
