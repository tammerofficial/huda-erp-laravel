@extends('layouts.app')

@section('title', 'Create BOM')
@section('page-title', 'Bill of Materials')

@section('content')
<div class="">
    <!-- Header Card -->
    <div class="luxury-card mb-4">
        <div class="p-4 border-bottom" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1" style="color: #d4af37; font-weight: 600;">
                        <i class="fas fa-list-alt"></i> Create BOM
                    </h3>
                    <p class="mb-0" style="color: #ffffff; opacity: 0.9;">
                        Product: <strong>{{ $product->name }}</strong> <span class="badge bg-secondary">{{ $product->sku }}</span>
                    </p>
                </div>
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Product
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('products.bom.store', $product) }}" method="POST" id="bomForm">
        @csrf
        
        <!-- BOM Details Card -->
        <div class="luxury-card mb-4">
            <div class="p-4 border-bottom">
                <h4 class="section-title mb-0">
                    <i class="fas fa-info-circle"></i> BOM Details
                </h4>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="">
                        <label for="version" class="form-label fw-semibold">
                            Version <span style="color: #d4af37;">*</span>
                        </label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('version') border-red-500 @enderror" 
                               id="version" name="version" value="{{ old('version', '1.0') }}" 
                               placeholder="e.g., 1.0, 2.0" required>
                        @error('version')
                            <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('description') border-red-500 @enderror" 
                               id="description" name="description" value="{{ old('description') }}"
                               placeholder="Brief description of this BOM">
                        @error('description')
                            <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- BOM Items Card -->
        <div class="luxury-card mb-4">
            <div class="p-4 border-bottom">
                <h4 class="section-title mb-0">
                    <i class="fas fa-boxes"></i> BOM Items
                </h4>
            </div>
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f8f8;">
                            <tr>
                                <th style="width: 35%;">Material</th>
                                <th style="width: 15%;">Quantity</th>
                                <th style="width: 15%;">Unit</th>
                                <th style="width: 15%;">Unit Cost</th>
                                <th style="width: 15%;">Total Cost</th>
                                <th style="width: 5%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="bomItems">
                            <tr class="item-row">
                                <td>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent material-select" name="items[0][material_id]" required>
                                        <option value="">Select Material</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" data-cost="{{ $material->unit_cost }}">
                                                {{ $material->name }} - {{ number_format($material->unit_cost, 2) }} KWD
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.001" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent quantity-input" 
                                           name="items[0][quantity]" min="0.001" value="1" required>
                                </td>
                                <td>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent unit-input" name="items[0][unit]" required>
                                        <option value="piece">Piece (قطعة)</option>
                                        <option value="meter">Meter (متر)</option>
                                        <option value="cm">Centimeter (سم)</option>
                                        <option value="kg">Kilogram (كيلو)</option>
                                        <option value="gram">Gram (جرام)</option>
                                        <option value="box">Box (علبة)</option>
                                        <option value="carton">Carton (كرتون)</option>
                                        <option value="roll">Roll (لفة)</option>
                                        <option value="yard">Yard (ياردة)</option>
                                        <option value="dozen">Dozen (دستة)</option>
                                        <option value="pack">Pack (حزمة)</option>
                                        <option value="bottle">Bottle (قارورة)</option>
                                        <option value="can">Can (علبة معدنية)</option>
                                        <option value="set">Set (طقم)</option>
                                        <option value="pair">Pair (زوج)</option>
                                        <option value="bundle">Bundle (رزمة)</option>
                                        <option value="spool">Spool (بكرة خيوط)</option>
                                        <option value="strand">Strand (خصلة/خيط)</option>
                                        <option value="card">Card (كرت/بطاقة)</option>
                                        <option value="sheet">Sheet (ورقة/قطعة)</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent cost-input" 
                                           readonly style="background-color: #f8f8f8;">
                                </td>
                                <td>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent total-input" 
                                           readonly style="background-color: #f8f8f8; font-weight: 600;">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addItem">
                        <i class="fas fa-plus"></i> Add Material
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="luxury-card mb-4">
            <div class="p-4 border-bottom" style="background-color: #f8f8f8;">
                <h4 class="section-title mb-0">
                    <i class="fas fa-calculator"></i> BOM Summary
                </h4>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="">
                        <div class="stat-card">
                            <div class="stat-label">Total Items</div>
                            <div class="stat-number" id="totalItems" style="color: #1a1a1a;">1</div>
                        </div>
                    </div>
                    <div class="">
                        <div class="stat-card gold-accent">
                            <div class="stat-label">Total Cost</div>
                            <div class="stat-number">
                                <span id="totalCost">0.00</span> <small>KWD</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn-primary" style="background-color: #d4af37; border-color: #d4af37;">
                <i class="fas fa-save"></i> Save BOM
            </button>
        </div>
    </form>
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
                input.name = input.name.replace(/\[\d+\]/, '[' + itemIndex + ']');
            }
            if (!input.readOnly && input.type !== 'hidden') {
                if (input.classList.contains('material-select')) {
                    input.selectedIndex = 0;
                } else if (input.classList.contains('unit-input')) {
                    input.selectedIndex = 0; // Reset to first option (piece)
                } else if (input.classList.contains('quantity-input')) {
                    input.value = '1';
                } else {
                    input.value = '';
                }
            }
        });
        
        // Show remove button
        newItem.querySelector('.remove-item').style.display = 'inline-block';
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
        updateSummary();
        updateRemoveButtons();
    });
    
    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            const itemRow = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                itemRow.remove();
                updateSummary();
                updateRemoveButtons();
            } else {
                alert('You must have at least one item in the BOM.');
            }
        }
    });
    
    // Calculate totals
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('material-select')) {
            const cost = e.target.selectedOptions[0].dataset.cost || 0;
            const costInput = e.target.closest('.item-row').querySelector('.cost-input');
            costInput.value = parseFloat(cost).toFixed(2);
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
    
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.item-row');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-item');
            if (items.length > 1) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
    
    // Initial update
    updateRemoveButtons();
});
</script>
@endsection
