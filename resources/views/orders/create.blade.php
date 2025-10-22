@extends('layouts.app')

@section('title', 'Add New Order')
@section('page-title', 'Add New Order')

@section('content')
<div x-data="orderForm()">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">🛒 Add New Order</h2>
                    <p class="text-gray-600 mt-1">Create a new customer order</p>
                </div>
                <a href="{{ route('orders.index') }}" 
                   class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Back to Orders
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('orders.store') }}" method="POST" id="orderForm" class="space-y-6">
            @csrf
            
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
                            @foreach(\App\Models\Customer::all() as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Order Number
                        </label>
                        <input type="text" name="order_number" id="order_number"
                               value="{{ old('order_number') }}"
                               placeholder="Auto-generated if empty"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('order_number') border-red-500 @enderror">
                        @error('order_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Order Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="order_date" id="order_date" required
                               value="{{ old('order_date', now()->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('order_date') border-red-500 @enderror">
                        @error('order_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Delivery Date
                        </label>
                        <input type="date" name="delivery_date" id="delivery_date"
                               value="{{ old('delivery_date') }}"
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
                    <i class="fas fa-shopping-cart mr-2 text-green-600"></i>
                    Order Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="status" id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="in_production" {{ old('status') == 'in_production' ? 'selected' : '' }}>In Production</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority
                        </label>
                        <select name="priority" id="priority"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Status
                        </label>
                        <select name="payment_status" id="payment_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_status') border-red-500 @enderror">
                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="overdue" {{ old('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                        @error('payment_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method
                        </label>
                        <select name="payment_method" id="payment_method"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-list mr-2 text-purple-600"></i>
                    Order Items
                </h3>
                
                <div id="order-items">
                    <div class="order-item border rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                                <select name="items[0][product_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    <option value="">Select Product</option>
                                    @foreach(\App\Models\Product::all() as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <input type="number" name="items[0][quantity]" min="1" value="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                                <input type="number" name="items[0][unit_price]" step="0.01" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="removeItem(this)" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" onclick="addItem()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add Item
                </button>
            </div>

            <!-- Order Totals -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-calculator mr-2 text-orange-600"></i>
                    Order Totals
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-2">
                            Subtotal (KWD)
                        </label>
                        <input type="number" name="subtotal" id="subtotal" step="0.01" min="0"
                               value="{{ old('subtotal') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('subtotal') border-red-500 @enderror">
                        @error('subtotal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Tax Amount (KWD)
                        </label>
                        <input type="number" name="tax_amount" id="tax_amount" step="0.01" min="0"
                               value="{{ old('tax_amount', 0) }}"
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
                               value="{{ old('discount_amount', 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('discount_amount') border-red-500 @enderror">
                        @error('discount_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Shipping Cost (KWD)
                        </label>
                        <input type="number" name="shipping_cost" id="shipping_cost" step="0.01" min="0"
                               value="{{ old('shipping_cost', 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_cost') border-red-500 @enderror">
                        @error('shipping_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                    Additional Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Billing Address
                        </label>
                        <textarea name="billing_address" id="billing_address" rows="3"
                                  placeholder="Enter billing address..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('billing_address') border-red-500 @enderror">{{ old('billing_address') }}</textarea>
                        @error('billing_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Shipping Address
                        </label>
                        <textarea name="shipping_address" id="shipping_address" rows="3"
                                  placeholder="Enter shipping address..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Order Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Enter any additional notes..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                        Create Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function orderForm() {
    let itemCount = 1;
    
    return {
        addItem() {
            const container = document.getElementById('order-items');
            const newItem = document.createElement('div');
            newItem.className = 'order-item border rounded-lg p-4 mb-4';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                        <select name="items[${itemCount}][product_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            <option value="">Select Product</option>
                            @foreach(\App\Models\Product::all() as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" name="items[${itemCount}][quantity]" min="1" value="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                        <input type="number" name="items[${itemCount}][unit_price]" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeItem(this)" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            itemCount++;
        }
    }
}

function removeItem(button) {
    button.closest('.order-item').remove();
}
</script>
@endsection