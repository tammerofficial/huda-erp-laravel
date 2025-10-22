@extends('layouts.app')

@section('title', 'Create BOM')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create BOM for: {{ $product->name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.bom.store', $product) }}" method="POST" id="bomForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="version">Version <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('version') is-invalid @enderror" id="version" name="version" value="{{ old('version', '1.0') }}" required>
                                    @error('version')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}">
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h5>BOM Items</h5>
                                <div id="bomItems">
                                    <div class="row item-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Material</label>
                                                <select class="form-control material-select" name="items[0][material_id]" required>
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
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" step="0.001" class="form-control quantity-input" name="items[0][quantity]" min="0.001" value="1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Unit</label>
                                                <input type="text" class="form-control unit-input" name="items[0][unit]" value="piece" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Unit Cost</label>
                                                <input type="number" step="0.01" class="form-control cost-input" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Total Cost</label>
                                                <input type="number" step="0.01" class="form-control total-input" readonly>
                                            </div>
                                        </div>
                                    </div>
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
                                        <h5>BOM Summary</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Total Items: </strong><span id="totalItems">1</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Total Cost: </strong><span id="totalCost">0.00</span> KWD
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save BOM
                            </button>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
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
        const itemsContainer = document.getElementById('bomItems');
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
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
        updateSummary();
    });
    
    // Calculate totals
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('material-select')) {
            const cost = e.target.selectedOptions[0].dataset.cost;
            const costInput = e.target.closest('.item-row').querySelector('.cost-input');
            costInput.value = cost;
            calculateTotal(e.target.closest('.item-row'));
        }
        
        if (e.target.classList.contains('quantity-input')) {
            calculateTotal(e.target.closest('.item-row'));
        }
    });
    
    function calculateTotal(itemRow) {
        const quantity = parseFloat(itemRow.querySelector('.quantity-input').value) || 0;
        const cost = parseFloat(itemRow.querySelector('.cost-input').value) || 0;
        const total = quantity * cost;
        itemRow.querySelector('.total-input').value = total.toFixed(2);
        updateSummary();
    }
    
    function updateSummary() {
        const items = document.querySelectorAll('.item-row');
        const totalItems = items.length;
        let totalCost = 0;
        
        items.forEach(item => {
            const total = parseFloat(item.querySelector('.total-input').value) || 0;
            totalCost += total;
        });
        
        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalCost').textContent = totalCost.toFixed(2);
    }
});
</script>
@endsection
