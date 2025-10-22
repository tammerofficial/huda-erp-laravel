@extends('layouts.app')

@section('title', 'New Purchase Order')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">New Purchase Order</h3>
                </div>
                <div class="">
                    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('supplier_id') border-red-500 @enderror" id="supplier_id" name="supplier_id" required>
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">Order Date <span class="text-danger">*</span></label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('order_date') border-red-500 @enderror" id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                                    @error('order_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('delivery_date') border-red-500 @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}">
                                    @error('delivery_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('priority') border-red-500 @enderror" id="priority" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_terms') border-red-500 @enderror" id="payment_terms" name="payment_terms" value="{{ old('payment_terms') }}">
                                    @error('payment_terms')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_address') border-red-500 @enderror" id="shipping_address" name="shipping_address" rows="2">{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <h5>Order Items</h5>
                                <div id="purchaseItems">
                                    <div class="row item-row">
                                        <div class="col-md-4">
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent material-select" name="items[0][material_id]" required>
                                                    <option value="">Select Material</option>
                                                    @foreach($materials as $material)
                                                        <option value="{{ $material->id }}" data-cost="{{ $material->unit_cost }}">
                                                            {{ $material->name }} - {{ number_format($material->unit_cost, 2) }} KWD
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                                <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent quantity-input" name="items[0][quantity]" min="1" value="1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                                                <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent price-input" name="items[0][unit_price]" min="0" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                                                <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent total-input" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block remove-item" style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success" id="addItem">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Purchase Order
                            </button>
                            <a href="{{ route('purchases.index') }}" class="btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    
    // Add new item
    document.getElementById('addItem').addEventListener('click', function() {
        const itemsContainer = document.getElementById('purchaseItems');
        const newItem = document.querySelector('.item-row').cloneNode(true);
        
        // Update names and clear values
        newItem.querySelectorAll('select, input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace('[0]', '[' + itemIndex + ']');
            }
            if (input.value && input.type !== 'hidden') {
                input.value = '';
            }
        });
        
        // Show remove button
        newItem.querySelector('.remove-item').style.display = 'block';
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
    });
    
    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
    
    // Calculate totals
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('material-select')) {
            const cost = e.target.selectedOptions[0].dataset.cost;
            const priceInput = e.target.closest('.item-row').querySelector('.price-input');
            priceInput.value = cost;
            calculateTotal(e.target.closest('.item-row'));
        }
        
        if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
            calculateTotal(e.target.closest('.item-row'));
        }
    });
    
    function calculateTotal(itemRow) {
        const quantity = parseFloat(itemRow.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(itemRow.querySelector('.price-input').value) || 0;
        const total = quantity * price;
        itemRow.querySelector('.total-input').value = total.toFixed(2);
    }
});
</script>
@endsection
