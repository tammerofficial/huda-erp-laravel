@extends('layouts.app')

@section('title', 'Edit Order Items')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Items for Order: {{ $order->order_number }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update-items', $order) }}" method="POST" id="orderItemsForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Order Items</h5>
                                <div id="orderItems">
                                    @foreach($order->orderItems as $index => $item)
                                    <div class="row item-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Product</label>
                                                <select class="form-control product-select" name="items[{{ $index }}][product_id]" required>
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }} - {{ number_format($product->price, 2) }} KWD
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" class="form-control quantity-input" name="items[{{ $index }}][quantity]" min="1" value="{{ $item->quantity }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Unit Price</label>
                                                <input type="number" step="0.01" class="form-control price-input" name="items[{{ $index }}][unit_price]" min="0" value="{{ $item->unit_price }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Total</label>
                                                <input type="number" step="0.01" class="form-control total-input" readonly value="{{ $item->total_price }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block remove-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-success" id="addItem">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Order Summary</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Total Items: </strong><span id="totalItems">{{ $order->orderItems->count() }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Total Amount: </strong><span id="totalAmount">{{ number_format($order->total_amount, 2) }}</span> KWD
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
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
    let itemIndex = {{ $order->orderItems->count() }};
    
    // Add new item
    document.getElementById('addItem').addEventListener('click', function() {
        const itemsContainer = document.getElementById('orderItems');
        const newItem = document.querySelector('.item-row').cloneNode(true);
        
        // Update names and clear values
        newItem.querySelectorAll('select, input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, '[' + itemIndex + ']');
            }
            if (input.value && input.type !== 'hidden') {
                input.value = '';
            }
        });
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
        updateSummary();
    });
    
    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
            updateSummary();
        }
    });
    
    // Calculate totals
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const price = e.target.selectedOptions[0].dataset.price;
            const priceInput = e.target.closest('.item-row').querySelector('.price-input');
            priceInput.value = price;
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
        updateSummary();
    }
    
    function updateSummary() {
        const items = document.querySelectorAll('.item-row');
        const totalItems = items.length;
        let totalAmount = 0;
        
        items.forEach(item => {
            const total = parseFloat(item.querySelector('.total-input').value) || 0;
            totalAmount += total;
        });
        
        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
    }
});
</script>
@endsection
