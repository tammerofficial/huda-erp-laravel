@extends('layouts.app')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('content')
<div x-data="orderDetails()">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">📋 Order Details: {{ $order->order_number }}</h2>
                    <p class="text-gray-600 mt-1">Complete order information and items</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('orders.edit', $order) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('orders.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Order Information
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Order Number:</span>
                        <span class="text-gray-900 font-mono">{{ $order->order_number }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Customer:</span>
                        <span class="text-gray-900">{{ $order->customer->name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Order Date:</span>
                        <span class="text-gray-900">{{ $order->order_date->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status == 'completed') bg-green-100 text-green-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @elseif($order->status == 'in-production') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst(str_replace('-', ' ', $order->status)) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Payment Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($order->payment_status == 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status == 'overdue') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Priority:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($order->priority == 'urgent') bg-red-100 text-red-800
                            @elseif($order->priority == 'high') bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($order->priority) }}
                        </span>
                    </div>
                    
                    @if($order->delivery_date)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Delivery Date:</span>
                        <span class="text-gray-900">{{ $order->delivery_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calculator mr-2 text-green-600"></i>
                    Financial Summary
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Subtotal:</span>
                        <span class="text-gray-900 font-mono">{{ number_format($order->total_amount, 2) }} KWD</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Tax:</span>
                        <span class="text-gray-900 font-mono">{{ number_format($order->tax_amount, 2) }} KWD</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Discount:</span>
                        <span class="text-gray-900 font-mono">{{ number_format($order->discount_amount, 2) }} KWD</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 bg-gray-50 rounded-lg px-4">
                        <span class="font-bold text-gray-900">Final Amount:</span>
                        <span class="text-xl font-bold text-blue-600 font-mono">{{ number_format($order->final_amount, 2) }} KWD</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        @if($order->notes || $order->shipping_address)
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-comment mr-2 text-purple-600"></i>
                Additional Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($order->notes)
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Notes</h4>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $order->notes }}</p>
                </div>
                @endif
                
                @if($order->shipping_address)
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Shipping Address</h4>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $order->shipping_address }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-shopping-cart mr-2 text-orange-600"></i>
                Order Items
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->orderItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">{{ number_format($item->unit_price, 2) }} KWD</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-mono">{{ number_format($item->total_price, 2) }} KWD</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total Amount:</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 font-mono">{{ number_format($order->total_amount, 2) }} KWD</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Production Orders -->
        @if($order->productionOrders->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-industry mr-2 text-indigo-600"></i>
                Production Orders
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Production Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->productionOrders as $productionOrder)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $productionOrder->production_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $productionOrder->product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $productionOrder->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($productionOrder->status == 'completed') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst(str_replace('-', ' ', $productionOrder->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($productionOrder->priority == 'urgent') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($productionOrder->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $productionOrder->due_date ? $productionOrder->due_date->format('M d, Y') : 'Not Set' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function orderDetails() {
    return {
        init() {
            // Initialize any functionality needed for order details
        }
    }
}
</script>
@endsection