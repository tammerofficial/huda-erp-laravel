@extends('layouts.app')

@section('title', 'Edit Material')
@section('page-title', 'Edit Material')

@section('content')
<div>
    <form action="{{ route('materials.update', $material) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Material: {{ $material->name }}</h2>
                    <p class="text-gray-600 mt-1">Update material information and specifications</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('materials.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Material Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $material->name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        SKU <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="sku" 
                           value="{{ old('sku', $material->sku) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('sku') border-red-500 @enderror">
                    @error('sku')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit <span class="text-red-500">*</span>
                    </label>
                    <select name="unit" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('unit') border-red-500 @enderror">
                        <option value="">Select Unit</option>
                        <option value="piece" {{ old('unit', $material->unit) == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="meter" {{ old('unit', $material->unit) == 'meter' ? 'selected' : '' }}>Meter</option>
                        <option value="sqm" {{ old('unit', $material->unit) == 'sqm' ? 'selected' : '' }}>Square Meter</option>
                        <option value="cubic_meter" {{ old('unit', $material->unit) == 'cubic_meter' ? 'selected' : '' }}>Cubic Meter</option>
                        <option value="cm" {{ old('unit', $material->unit) == 'cm' ? 'selected' : '' }}>Centimeter</option>
                        <option value="kg" {{ old('unit', $material->unit) == 'kg' ? 'selected' : '' }}>Kilogram</option>
                        <option value="gram" {{ old('unit', $material->unit) == 'gram' ? 'selected' : '' }}>Gram</option>
                        <option value="liter" {{ old('unit', $material->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                        <option value="pack" {{ old('unit', $material->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                        <option value="spool" {{ old('unit', $material->unit) == 'spool' ? 'selected' : '' }}>Spool</option>
                        <option value="box" {{ old('unit', $material->unit) == 'box' ? 'selected' : '' }}>Box</option>
                    </select>
                    @error('unit')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Cost (KWD) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="unit_cost" 
                           value="{{ old('unit_cost', $material->unit_cost) }}"
                           step="0.001"
                           min="0"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('unit_cost') border-red-500 @enderror">
                    @error('unit_cost')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <option value="">Select Category</option>
                        <option value="silk" {{ old('category', $material->category) == 'silk' ? 'selected' : '' }}>Silk</option>
                        <option value="satin" {{ old('category', $material->category) == 'satin' ? 'selected' : '' }}>Satin</option>
                        <option value="chiffon" {{ old('category', $material->category) == 'chiffon' ? 'selected' : '' }}>Chiffon</option>
                        <option value="tulle" {{ old('category', $material->category) == 'tulle' ? 'selected' : '' }}>Tulle</option>
                        <option value="lace" {{ old('category', $material->category) == 'lace' ? 'selected' : '' }}>Lace</option>
                        <option value="velvet" {{ old('category', $material->category) == 'velvet' ? 'selected' : '' }}>Velvet</option>
                        <option value="brocade" {{ old('category', $material->category) == 'brocade' ? 'selected' : '' }}>Brocade</option>
                        <option value="leather" {{ old('category', $material->category) == 'leather' ? 'selected' : '' }}>Leather</option>
                        <option value="crystals" {{ old('category', $material->category) == 'crystals' ? 'selected' : '' }}>Crystals</option>
                        <option value="beads" {{ old('category', $material->category) == 'beads' ? 'selected' : '' }}>Beads</option>
                        <option value="sequins" {{ old('category', $material->category) == 'sequins' ? 'selected' : '' }}>Sequins</option>
                        <option value="stones" {{ old('category', $material->category) == 'stones' ? 'selected' : '' }}>Stones</option>
                        <option value="thread" {{ old('category', $material->category) == 'thread' ? 'selected' : '' }}>Thread</option>
                        <option value="zipper" {{ old('category', $material->category) == 'zipper' ? 'selected' : '' }}>Zipper</option>
                        <option value="buttons" {{ old('category', $material->category) == 'buttons' ? 'selected' : '' }}>Buttons</option>
                        <option value="other" {{ old('category', $material->category) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <select name="supplier_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <option value="">Select Supplier</option>
                        @foreach(\App\Models\Supplier::where('is_active', true)->orderBy('name')->get() as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $material->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <input type="text" 
                           name="color" 
                           value="{{ old('color', $material->color) }}"
                           placeholder="e.g., Ivory, Rose Gold, Burgundy..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                    <select name="size" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <option value="">Select Size (Optional)</option>
                        <optgroup label="Fabric Widths">
                            @foreach(config('units.sizes.fabric_width', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size', $material->size) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Button Sizes">
                            @foreach(config('units.sizes.button', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size', $material->size) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Zipper Lengths">
                            @foreach(config('units.sizes.zipper', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size', $material->size) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Thread Sizes">
                            @foreach(config('units.sizes.thread', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size', $material->size) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Pearl/Bead Sizes">
                            @foreach(config('units.sizes.pearl_bead', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size', $material->size) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Ribbon/Trim Widths">
                            @foreach(config('units.sizes.ribbon_trim', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size', $material->size) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Garment Sizes">
                            @foreach(config('units.sizes.garment', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size', $material->size) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Generic Sizes">
                            @foreach(config('units.sizes.generic', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size', $material->size) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>

        <!-- Inventory Settings -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">📦 Inventory & Auto-Purchase Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reorder Level</label>
                    <input type="number" 
                           name="reorder_level" 
                           value="{{ old('reorder_level', $material->reorder_level) }}"
                           min="0"
                           placeholder="Minimum stock before reordering"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Alert when stock falls below this level</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Stock</label>
                    <input type="number" 
                           name="max_stock" 
                           value="{{ old('max_stock', $material->max_stock) }}"
                           min="0"
                           placeholder="Maximum storage capacity"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Maximum quantity to keep in stock</p>
                </div>
            </div>

            <!-- Auto Purchase Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-robot text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Automatic Purchase Orders</h4>
                            <p class="text-sm text-gray-600 mt-1">Enable automatic purchase orders when stock is low</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="auto_purchase_enabled" 
                               value="1"
                               {{ old('auto_purchase_enabled', $material->auto_purchase_enabled) ? 'checked' : '' }}
                               class="sr-only peer"
                               onchange="document.getElementById('auto-purchase-fields').classList.toggle('hidden')">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div id="auto-purchase-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('auto_purchase_enabled', $material->auto_purchase_enabled) ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exclamation-triangle text-orange-500 mr-1"></i>
                            Minimum Stock Level (Trigger)
                        </label>
                        <input type="number" 
                               name="min_stock_level" 
                               value="{{ old('min_stock_level', $material->min_stock_level ?? 5) }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-600">
                            <i class="fas fa-info-circle"></i>
                            Auto-purchase order will be created when stock reaches this level
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-shopping-cart text-green-500 mr-1"></i>
                            Auto-Purchase Quantity
                        </label>
                        <input type="number" 
                               name="auto_purchase_quantity" 
                               value="{{ old('auto_purchase_quantity', $material->auto_purchase_quantity ?? 50) }}"
                               min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-600">
                            <i class="fas fa-info-circle"></i>
                            Quantity to order automatically when triggered
                        </p>
                    </div>
                </div>

                <div class="mt-4 bg-white rounded-lg p-3 border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2 mt-0.5"></i>
                        <div class="text-xs text-gray-700">
                            <strong>How it works:</strong>
                            <ul class="list-disc ml-4 mt-1 space-y-1">
                                <li>System checks stock levels daily</li>
                                <li>When stock ≤ Minimum Level, a purchase order is auto-created</li>
                                <li>Order quantity is set to Auto-Purchase Quantity</li>
                                <li>Orders are sent to the selected supplier (if available)</li>
                                <li>You can review and approve orders before processing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Additional Information</h3>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" 
                              rows="4"
                              placeholder="Material specifications, characteristics, usage notes..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">{{ old('description', $material->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
                    <input type="url" 
                           name="image_url" 
                           value="{{ old('image_url', $material->image_url) }}"
                           placeholder="https://example.com/image.jpg"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    @if($material->image_url)
                        <div class="mt-3">
                            <img src="{{ $material->image_url }}" alt="{{ $material->name }}" class="h-32 w-32 object-cover rounded-lg border">
                        </div>
                    @endif
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active"
                           value="1"
                           {{ old('is_active', $material->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded">
                    <label for="is_active" class="mr-2 text-sm font-medium text-gray-700">
                        Active Material
                    </label>
                    <p class="text-xs text-gray-500">(Inactive materials won't appear in selections)</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('materials.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
