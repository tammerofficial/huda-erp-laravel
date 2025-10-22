@extends('layouts.app')

@section('title', 'Edit Order')
@section('page-title', 'Edit Order')

@section('content')
<div x-data="orderForm()">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">✏️ Edit Order: {{ $order->order_number }}</h2>
                    <p class="text-gray-600 mt-1">Update order information</p>
                </div>
                <a href="{{ route('orders.index') }}" 
                   class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Back to Orders
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('orders.update', $order) }}" method="POST" id="orderForm" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Customer Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer <span class="text-red-500">*</span>
                        </label>
                        <select name="customer_id" id="customer_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('customer_id') border-red-500 @enderror">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Order Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="order_date" id="order_date" required
                               value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('order_date') border-red-500 @enderror">
                        @error('order_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-cog mr-2 text-green-600"></i>
                    Order Status
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="on-hold" {{ old('status', $order->status) == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="in-production" {{ old('status', $order->status) == 'in-production' ? 'selected' : '' }}>In Production</option>
                            <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Status
                        </label>
                        <select name="payment_status" id="payment_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_status') border-red-500 @enderror">
                            <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="partial" {{ old('payment_status', $order->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="overdue" {{ old('payment_status', $order->payment_status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                        @error('payment_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Type
                        </label>
                        <select name="payment_type" id="payment_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_type') border-red-500 @enderror">
                            <option value="">Select Payment Type</option>
                            <option value="cash" {{ old('payment_type', $order->payment_type) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="credit" {{ old('payment_type', $order->payment_type) == 'credit' ? 'selected' : '' }}>Credit</option>
                            <option value="bank_transfer" {{ old('payment_type', $order->payment_type) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="card" {{ old('payment_type', $order->payment_type) == 'card' ? 'selected' : '' }}>Card</option>
                        </select>
                        @error('payment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Delivery Date
                        </label>
                        <input type="date" name="delivery_date" id="delivery_date"
                               value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('delivery_date') border-red-500 @enderror">
                        @error('delivery_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-purple-600"></i>
                    Order Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority
                        </label>
                        <select name="priority" id="priority"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority', $order->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="normal" {{ old('priority', $order->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ old('priority', $order->priority) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority', $order->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Tax Amount (KWD)
                        </label>
                        <input type="number" name="tax_amount" id="tax_amount" step="0.01" min="0"
                               value="{{ old('tax_amount', $order->tax_amount) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('tax_amount') border-red-500 @enderror">
                        @error('tax_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Discount Amount (KWD)
                        </label>
                        <input type="number" name="discount_amount" id="discount_amount" step="0.01" min="0"
                               value="{{ old('discount_amount', $order->discount_amount) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('discount_amount') border-red-500 @enderror">
                        @error('discount_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="final_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Final Amount (KWD)
                        </label>
                        <input type="number" name="final_amount" id="final_amount" step="0.01" min="0"
                               value="{{ old('final_amount', $order->final_amount) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('final_amount') border-red-500 @enderror">
                        @error('final_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-comment mr-2 text-indigo-600"></i>
                    Additional Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  placeholder="Enter any additional notes..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Shipping Address
                        </label>
                        <textarea name="shipping_address" id="shipping_address" rows="3"
                                  placeholder="Enter shipping address..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                        @error('shipping_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex justify-end space-x-4 space-x-reverse">
                    <a href="{{ route('orders.index') }}" 
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 btn-primary transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function orderForm() {
    return {
        init() {
            // Initialize any form-specific functionality
        }
    }
}
</script>
@endsection