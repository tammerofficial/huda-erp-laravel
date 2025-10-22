@extends('layouts.app')

@section('title', 'Add New Material')
@section('page-title', 'Add New Material')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="luxury-card mb-4">
        <div class="p-4 border-bottom" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1" style="color: #d4af37; font-weight: 600;">
                        <i class="fas fa-plus-circle"></i> Add New Material
                    </h3>
                    <p class="mb-0" style="color: #ffffff; opacity: 0.9;">
                        Create a new material record for your inventory
                    </p>
                </div>
                <a href="{{ route('materials.index') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Materials
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information Card -->
        <div class="smart-form-card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="form-grid-2">
                    <!-- Material Name -->
                    <div class="form-group-enhanced">
                        <label for="name">Material Name <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-cube input-icon"></i>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g., Italian Silk - Ivory"
                                   required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div class="form-group-enhanced">
                        <label for="sku">SKU <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-barcode input-icon"></i>
                            <input type="text" 
                                   class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku') }}" 
                                   placeholder="e.g., SLK-ITL-001"
                                   required>
                        </div>
                        @error('sku')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="form-group-enhanced">
                        <label for="category">Category</label>
                        <div class="input-with-icon">
                            <i class="fas fa-folder input-icon"></i>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" 
                                    name="category">
                                <option value="">Select Category</option>
                                <option value="fabric" {{ old('category') == 'fabric' ? 'selected' : '' }}>Fabric</option>
                                <option value="silk" {{ old('category') == 'silk' ? 'selected' : '' }}>Silk</option>
                                <option value="cotton" {{ old('category') == 'cotton' ? 'selected' : '' }}>Cotton</option>
                                <option value="lace" {{ old('category') == 'lace' ? 'selected' : '' }}>Lace</option>
                                <option value="trim" {{ old('category') == 'trim' ? 'selected' : '' }}>Trim</option>
                                <option value="button" {{ old('category') == 'button' ? 'selected' : '' }}>Button</option>
                                <option value="zipper" {{ old('category') == 'zipper' ? 'selected' : '' }}>Zipper</option>
                                <option value="thread" {{ old('category') == 'thread' ? 'selected' : '' }}>Thread</option>
                                <option value="accessory" {{ old('category') == 'accessory' ? 'selected' : '' }}>Accessory</option>
                            </select>
                        </div>
                        @error('category')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Supplier -->
                    <div class="form-group-enhanced">
                        <label for="supplier_id">Supplier</label>
                        <div class="input-with-icon">
                            <i class="fas fa-truck input-icon"></i>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                    id="supplier_id" 
                                    name="supplier_id">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('supplier_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="form-group-enhanced form-grid-full">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Detailed description of the material...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Specifications Card -->
        <div class="smart-form-card">
            <div class="card-header">
                <h5><i class="fas fa-ruler-combined"></i> Specifications</h5>
            </div>
            <div class="card-body">
                <div class="form-grid-3">
                    <!-- Color -->
                    <div class="form-group-enhanced">
                        <label for="color">Color</label>
                        <div class="input-with-icon">
                            <i class="fas fa-palette input-icon"></i>
                            <input type="text" 
                                   class="form-control @error('color') is-invalid @enderror" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color') }}" 
                                   placeholder="e.g., Ivory, Black">
                        </div>
                        @error('color')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Size -->
                    <div class="form-group-enhanced">
                        <label for="size">Size</label>
                        <div class="input-with-icon">
                            <i class="fas fa-expand input-icon"></i>
                            <input type="text" 
                                   class="form-control @error('size') is-invalid @enderror" 
                                   id="size" 
                                   name="size" 
                                   value="{{ old('size') }}" 
                                   placeholder="e.g., 150cm width">
                        </div>
                        @error('size')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div class="form-group-enhanced">
                        <label for="unit">Unit <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-balance-scale input-icon"></i>
                            <select class="form-select @error('unit') is-invalid @enderror" 
                                    id="unit" 
                                    name="unit" 
                                    required>
                                <option value="">Select Unit</option>
                                <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece (قطعة)</option>
                                <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meter (متر)</option>
                                <option value="cm" {{ old('unit') == 'cm' ? 'selected' : '' }}>Centimeter (سم)</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (كيلو)</option>
                                <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram (جرام)</option>
                                <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box (علبة)</option>
                                <option value="carton" {{ old('unit') == 'carton' ? 'selected' : '' }}>Carton (كرتون)</option>
                                <option value="roll" {{ old('unit') == 'roll' ? 'selected' : '' }}>Roll (لفة)</option>
                                <option value="yard" {{ old('unit') == 'yard' ? 'selected' : '' }}>Yard (ياردة)</option>
                                <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen (دستة)</option>
                                <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack (حزمة)</option>
                                <option value="spool" {{ old('unit') == 'spool' ? 'selected' : '' }}>Spool (بكرة خيوط)</option>
                                <option value="strand" {{ old('unit') == 'strand' ? 'selected' : '' }}>Strand (خصلة/خيط)</option>
                            </select>
                        </div>
                        @error('unit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Stock Card -->
        <div class="smart-form-card">
            <div class="card-header">
                <h5><i class="fas fa-coins"></i> Pricing & Stock</h5>
            </div>
            <div class="card-body">
                <div class="form-grid-3">
                    <!-- Unit Cost -->
                    <div class="form-group-enhanced">
                        <label for="unit_cost">Unit Cost (KWD) <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-money-bill-wave input-icon"></i>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('unit_cost') is-invalid @enderror" 
                                   id="unit_cost" 
                                   name="unit_cost" 
                                   value="{{ old('unit_cost') }}" 
                                   placeholder="0.00"
                                   required>
                        </div>
                        @error('unit_cost')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div class="form-group-enhanced">
                        <label for="reorder_level">Reorder Level <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-level-down-alt input-icon"></i>
                            <input type="number" 
                                   class="form-control @error('reorder_level') is-invalid @enderror" 
                                   id="reorder_level" 
                                   name="reorder_level" 
                                   value="{{ old('reorder_level', 10) }}" 
                                   required>
                        </div>
                        <small class="form-text">Alert when stock reaches this level</small>
                        @error('reorder_level')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Max Stock -->
                    <div class="form-group-enhanced">
                        <label for="max_stock">Max Stock</label>
                        <div class="input-with-icon">
                            <i class="fas fa-level-up-alt input-icon"></i>
                            <input type="number" 
                                   class="form-control @error('max_stock') is-invalid @enderror" 
                                   id="max_stock" 
                                   name="max_stock" 
                                   value="{{ old('max_stock') }}" 
                                   placeholder="Optional">
                        </div>
                        <small class="form-text">Maximum stock level</small>
                        @error('max_stock')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Upload Card -->
        <div class="smart-form-card">
            <div class="card-header">
                <h5><i class="fas fa-image"></i> Material Image</h5>
            </div>
            <div class="card-body">
                <div class="file-upload-area" id="fileUploadArea">
                    <input type="file" 
                           id="image" 
                           name="image" 
                           class="d-none" 
                           accept="image/*">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">Click to upload or drag and drop</div>
                    <div class="upload-hint">PNG, JPG, GIF up to 2MB</div>
                </div>
                <div id="imagePreview" class="mt-3 d-none">
                    <div class="image-preview">
                        <img id="previewImg" src="" alt="Preview">
                        <button type="button" class="remove-image" onclick="removeImage()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Card -->
        <div class="smart-form-card">
            <div class="card-header">
                <h5><i class="fas fa-sticky-note"></i> Additional Notes</h5>
            </div>
            <div class="card-body">
                <div class="form-group-enhanced">
                    <label for="notes">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" 
                              name="notes" 
                              rows="3" 
                              placeholder="Any additional information...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-times"></i> Cancel
            </a>
            <div class="btn-group">
                <button type="submit" name="action" value="save" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-save"></i> Save
                </button>
                <button type="submit" name="action" value="save_and_new" class="btn btn-lg" style="background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%); color: white; border: none;">
                    <i class="fas fa-plus"></i> Save & Add Another
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// File Upload Handler
document.getElementById('fileUploadArea').addEventListener('click', function() {
    document.getElementById('image').click();
});

document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('d-none');
}

// Drag and Drop
const uploadArea = document.getElementById('fileUploadArea');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, () => {
        uploadArea.style.background = 'linear-gradient(135deg, #fff9e6 0%, #fffbf0 100%)';
    });
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, () => {
        uploadArea.style.background = '';
    });
});

uploadArea.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('image').files = files;
    
    const event = new Event('change');
    document.getElementById('image').dispatchEvent(event);
});
</script>
@endsection

