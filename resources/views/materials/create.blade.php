@extends('layouts.app')

@section('title', 'Create New Material')
@section('page-title', 'Create Material')

@section('content')
<div>
    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Create New Material</h2>
                    <p class="text-gray-600 mt-1">Add a new material to your inventory</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('materials.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Create Material
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
                           value="{{ old('name') }}"
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
                           value="{{ old('sku') }}"
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
                        <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meter</option>
                        <option value="sqm" {{ old('unit') == 'sqm' ? 'selected' : '' }}>Square Meter</option>
                        <option value="cubic_meter" {{ old('unit') == 'cubic_meter' ? 'selected' : '' }}>Cubic Meter</option>
                        <option value="cm" {{ old('unit') == 'cm' ? 'selected' : '' }}>Centimeter</option>
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                        <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                        <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                        <option value="spool" {{ old('unit') == 'spool' ? 'selected' : '' }}>Spool</option>
                        <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
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
                           value="{{ old('unit_cost') }}"
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
                        <option value="silk" {{ old('category') == 'silk' ? 'selected' : '' }}>Silk</option>
                        <option value="satin" {{ old('category') == 'satin' ? 'selected' : '' }}>Satin</option>
                        <option value="chiffon" {{ old('category') == 'chiffon' ? 'selected' : '' }}>Chiffon</option>
                        <option value="tulle" {{ old('category') == 'tulle' ? 'selected' : '' }}>Tulle</option>
                        <option value="lace" {{ old('category') == 'lace' ? 'selected' : '' }}>Lace</option>
                        <option value="velvet" {{ old('category') == 'velvet' ? 'selected' : '' }}>Velvet</option>
                        <option value="brocade" {{ old('category') == 'brocade' ? 'selected' : '' }}>Brocade</option>
                        <option value="leather" {{ old('category') == 'leather' ? 'selected' : '' }}>Leather</option>
                        <option value="crystals" {{ old('category') == 'crystals' ? 'selected' : '' }}>Crystals</option>
                        <option value="beads" {{ old('category') == 'beads' ? 'selected' : '' }}>Beads</option>
                        <option value="sequins" {{ old('category') == 'sequins' ? 'selected' : '' }}>Sequins</option>
                        <option value="stones" {{ old('category') == 'stones' ? 'selected' : '' }}>Stones</option>
                        <option value="thread" {{ old('category') == 'thread' ? 'selected' : '' }}>Thread</option>
                        <option value="zipper" {{ old('category') == 'zipper' ? 'selected' : '' }}>Zipper</option>
                        <option value="buttons" {{ old('category') == 'buttons' ? 'selected' : '' }}>Buttons</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <select name="supplier_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <option value="">Select Supplier</option>
                        @foreach(\App\Models\Supplier::where('is_active', true)->orderBy('name')->get() as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <input type="text" 
                           name="color" 
                           value="{{ old('color') }}"
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
                                <option value="{{ $key }}" {{ old('size') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Button Sizes">
                            @foreach(config('units.sizes.button', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Zipper Lengths">
                            @foreach(config('units.sizes.zipper', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Thread Sizes">
                            @foreach(config('units.sizes.thread', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Pearl/Bead Sizes">
                            @foreach(config('units.sizes.pearl_bead', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Ribbon/Trim Widths">
                            @foreach(config('units.sizes.ribbon_trim', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Garment Sizes">
                            @foreach(config('units.sizes.garment', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Generic Sizes">
                            @foreach(config('units.sizes.generic', []) as $key => $label)
                                <option value="{{ $key }}" {{ old('size') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>

        <!-- Inventory Settings -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Inventory Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reorder Level</label>
                    <input type="number" 
                           name="reorder_level" 
                           value="{{ old('reorder_level') }}"
                           min="0"
                           placeholder="Minimum stock before reordering"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Alert when stock falls below this level</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Stock</label>
                    <input type="number" 
                           name="max_stock" 
                           value="{{ old('max_stock') }}"
                           min="0"
                           placeholder="Maximum storage capacity"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Maximum quantity to keep in stock</p>
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
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
                    <input type="url" 
                           name="image_url" 
                           value="{{ old('image_url') }}"
                           placeholder="https://example.com/image.jpg"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active"
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
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
                Create Material
            </button>
        </div>
    </form>
</div>
@endsection
