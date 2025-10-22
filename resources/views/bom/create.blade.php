@extends('layouts.app')

@section('title', 'Create Bill of Materials')
@section('page-title', 'Create New BOM')

@section('content')
<div x-data="bomCreate()" x-init="init()">
    <form method="POST" action="{{ route('bom.store') }}" @submit="prepareSubmit">
        @csrf

        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Create Bill of Materials</h2>
                    <p class="text-gray-600 mt-1">Define material requirements for production</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('bom.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Save BOM
                    </button>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                    <select name="product_id" 
                            x-model="productId"
                            @change="loadProduct Suggestions()"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-name="{{ $product->name }}">
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Version *</label>
                    <input type="text" 
                           name="version" 
                           value="{{ old('version', '1.0') }}"
                           required
                           placeholder="e.g., 1.0, 2.0, V1..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <input type="text" 
                           name="description" 
                           value="{{ old('description') }}"
                           placeholder="Optional notes..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Materials Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Materials Required</h3>
                <button type="button" @click="addMaterial()" class="btn-success btn-sm">
                    <i class="fas fa-plus"></i>
                    Add Material
                </button>
            </div>

            <!-- Materials Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Material</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900" x-text="index + 1"></td>
                                <td class="px-4 py-4">
                                    <select :name="'items[' + index + '][material_id]'" 
                                            x-model="item.material_id"
                                            @change="updateMaterial(index)"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gray-900">
                                        <option value="">Select Material</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" 
                                                    data-unit="{{ $material->unit }}"
                                                    data-cost="{{ $material->unit_cost }}">
                                                {{ $material->name }} ({{ $material->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" 
                                           :name="'items[' + index + '][quantity]'"
                                           x-model="item.quantity"
                                           @input="updateTotal(index)"
                                           step="0.01"
                                           min="0.01"
                                           required
                                           class="w-24 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gray-900">
                                </td>
                                <td class="px-4 py-4">
                                    <input type="text" 
                                           :name="'items[' + index + '][unit]'"
                                           x-model="item.unit"
                                           readonly
                                           class="w-20 px-3 py-2 border border-gray-300 rounded bg-gray-50">
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span x-text="'KWD ' + parseFloat(item.unit_cost || 0).toFixed(3)"></span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    <span x-text="'KWD ' + parseFloat(item.total_cost || 0).toFixed(3)"></span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <button type="button" 
                                            @click="removeMaterial(index)" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <template x-if="items.length === 0">
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    No materials added yet. Click "Add Material" to start.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-left font-bold text-gray-900">Total BOM Cost:</td>
                            <td colspan="2" class="px-4 py-4 text-left font-bold text-gray-900">
                                <span x-text="'KWD ' + totalCost.toFixed(3)"></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-3">⚡ Quick Actions</h4>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="addMultipleMaterials()" class="btn-secondary btn-sm">
                        <i class="fas fa-layer-group"></i>
                        Add Multiple Materials
                    </button>
                    <button type="button" @click="clearAll()" class="btn-secondary btn-sm">
                        <i class="fas fa-trash"></i>
                        Clear All
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function bomCreate() {
    return {
        productId: '',
        items: [],
        totalCost: 0,

        init() {
            // Add one empty material by default
            this.addMaterial();
        },

        addMaterial() {
            this.items.push({
                material_id: '',
                quantity: 1,
                unit: '',
                unit_cost: 0,
                total_cost: 0
            });
        },

        addMultipleMaterials() {
            const count = prompt('How many materials do you want to add?', '5');
            if (count && !isNaN(count)) {
                for (let i = 0; i < parseInt(count); i++) {
                    this.addMaterial();
                }
            }
        },

        removeMaterial(index) {
            this.items.splice(index, 1);
            this.calculateTotal();
        },

        updateMaterial(index) {
            const select = event.target;
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                this.items[index].unit = option.dataset.unit;
                this.items[index].unit_cost = parseFloat(option.dataset.cost);
                this.updateTotal(index);
            }
        },

        updateTotal(index) {
            const item = this.items[index];
            item.total_cost = (item.quantity || 0) * (item.unit_cost || 0);
            this.calculateTotal();
        },

        calculateTotal() {
            this.totalCost = this.items.reduce((sum, item) => sum + (parseFloat(item.total_cost) || 0), 0);
        },

        clearAll() {
            if (confirm('Are you sure you want to clear all materials?')) {
                this.items = [];
                this.addMaterial();
            }
        },

        prepareSubmit(e) {
            if (this.items.length === 0) {
                e.preventDefault();
                alert('Please add at least one material');
                return false;
            }
        }
    }
}
</script>
@endsection

