@extends('layouts.app')

@section('title', 'Order Cost Breakdown')
@section('page-title', 'Order Cost Breakdown - ' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Order
        </a>
    </div>

    <!-- Order Info -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-600">Order Number</p>
                <p class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Customer</p>
                <p class="text-lg font-semibold text-gray-900">{{ $order->customer->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Order Date</p>
                <p class="text-lg font-semibold text-gray-900">{{ $order->order_date->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>

    <!-- Cost Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Costs Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Cost Breakdown</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Material Cost</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($breakdown['material_cost'], 2) }} KWD</span>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Labor Cost</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($breakdown['labor_cost'], 2) }} KWD</span>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Overhead Cost</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($breakdown['overhead_cost'], 2) }} KWD</span>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Shipping Cost</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($breakdown['shipping_cost'], 2) }} KWD</span>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg border-2 border-gray-300">
                    <span class="text-sm font-bold text-gray-700">Total Cost</span>
                    <span class="text-xl font-bold text-gray-900">{{ number_format($breakdown['total_cost'], 2) }} KWD</span>
                </div>
            </div>
        </div>

        <!-- Revenue & Profit -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Revenue & Profit</h3>
            <div class="space-y-6">
                <div class="p-6 bg-gradient-to-r from-green-50 to-green-100 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Total Revenue</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($breakdown['revenue'], 2) }} KWD</p>
                </div>
                
                <div class="p-6 bg-gradient-to-r from-red-50 to-red-100 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Total Cost</p>
                    <p class="text-3xl font-bold text-red-600">{{ number_format($breakdown['total_cost'], 2) }} KWD</p>
                </div>
                
                <div class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Net Profit</p>
                    <p class="text-3xl font-bold {{ $breakdown['profit'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        {{ number_format($breakdown['profit'], 2) }} KWD
                    </p>
                </div>
                
                <div class="p-6 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Profit Margin</p>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($breakdown['profit_margin'], 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Detail -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($item->product->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-10 h-10 rounded object-cover mr-3">
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right">{{ number_format($item->unit_price, 2) }} KWD</td>
                        <td class="px-6 py-4 text-right font-medium">{{ number_format($item->total_price, 2) }} KWD</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-500">
                            {{ number_format($item->product->cost ?? 0, 2) }} KWD
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Analytics -->
    @if($order->utm_source || $order->utm_medium || $order->utm_campaign)
    <div class="bg-white rounded-lg shadow-sm mt-6 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Marketing Analytics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($order->utm_source)
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600">UTM Source</p>
                <p class="font-semibold text-gray-900">{{ $order->utm_source }}</p>
            </div>
            @endif
            @if($order->utm_medium)
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">UTM Medium</p>
                <p class="font-semibold text-gray-900">{{ $order->utm_medium }}</p>
            </div>
            @endif
            @if($order->utm_campaign)
            <div class="p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-gray-600">UTM Campaign</p>
                <p class="font-semibold text-gray-900">{{ $order->utm_campaign }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="mt-6 flex gap-4">
        <form action="{{ route('orders.recalculate-costs', $order) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Recalculate Costs
            </button>
        </form>
    </div>
</div>
@endsection

