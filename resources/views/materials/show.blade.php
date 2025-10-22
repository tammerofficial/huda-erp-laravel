@extends('layouts.app')

@section('title', 'Material Details')
@section('page-title', 'Material Details')

@section('content')
<div class="container-fluid">
    <!-- Header Card -->
    <div class="luxury-card mb-4">
        <div class="p-4 border-bottom" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1" style="color: #d4af37; font-weight: 600;">
                        <i class="fas fa-cube"></i> {{ $material->name }}
                    </h3>
                    <p class="mb-0" style="color: #ffffff; opacity: 0.9;">
                        <span class="badge bg-secondary">{{ $material->sku }}</span>
                        @if($material->is_active)
                            <span class="badge bg-success ms-2">Active</span>
                        @else
                            <span class="badge bg-danger ms-2">Inactive</span>
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('materials.edit', $material) }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Image & Quick Stats -->
        <div class="col-lg-4">
            <!-- Material Image -->
            <div class="luxury-card mb-4">
                <div class="p-4">
                    @if($material->image_url)
                        <img src="{{ Storage::url($material->image_url) }}" alt="{{ $material->name }}" 
                             class="img-fluid rounded" style="width: 100%; object-fit: cover; max-height: 300px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center rounded" 
                             style="height: 300px; background: linear-gradient(135deg, #f8f8f8 0%, #e9e9e9 100%);">
                            <i class="fas fa-image" style="font-size: 4rem; color: #d4af37; opacity: 0.3;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-chart-bar"></i> Quick Stats</h5>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Unit Cost</span>
                            <strong style="color: #d4af37; font-size: 1.2rem;">{{ number_format($material->unit_cost, 2) }} KWD</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Unit</span>
                            <strong>{{ ucfirst($material->unit) }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Reorder Level</span>
                            <strong>{{ $material->reorder_level }}</strong>
                        </div>
                    </div>
                    @if($material->max_stock)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Max Stock</span>
                            <strong>{{ $material->max_stock }}</strong>
                        </div>
                    </div>
                    @endif
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total Inventory</span>
                            <strong class="badge bg-primary">{{ $material->inventories->sum('quantity') }} {{ $material->unit }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-info-circle"></i> Basic Information</h5>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Material Name</label>
                                <p class="mb-0 fw-semibold">{{ $material->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">SKU</label>
                                <p class="mb-0 fw-semibold">{{ $material->sku }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Category</label>
                                <p class="mb-0 fw-semibold">{{ $material->category ? ucfirst($material->category) : 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Supplier</label>
                                <p class="mb-0 fw-semibold">
                                    @if($material->supplier)
                                        <a href="{{ route('suppliers.show', $material->supplier) }}" style="color: #d4af37; text-decoration: none;">
                                            {{ $material->supplier->name }}
                                        </a>
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Color</label>
                                <p class="mb-0 fw-semibold">{{ $material->color ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Size</label>
                                <p class="mb-0 fw-semibold">{{ $material->size ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info-item">
                                <label class="text-muted mb-1">Description</label>
                                <p class="mb-0">{{ $material->description ?? 'No description available' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Created At</label>
                                <p class="mb-0 text-muted">{{ $material->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Last Updated</label>
                                <p class="mb-0 text-muted">{{ $material->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Section -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-warehouse"></i> Inventory by Warehouse</h5>
                </div>
                <div class="p-4">
                    @if($material->inventories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f8f8;">
                                    <tr>
                                        <th>Warehouse</th>
                                        <th>Quantity</th>
                                        <th>Reorder Level</th>
                                        <th>Max Level</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($material->inventories as $inventory)
                                    <tr>
                                        <td>
                                            <i class="fas fa-warehouse text-muted me-2"></i>
                                            {{ $inventory->warehouse->name }}
                                        </td>
                                        <td>
                                            <strong>{{ $inventory->quantity }} {{ $material->unit }}</strong>
                                        </td>
                                        <td>{{ $inventory->reorder_level }}</td>
                                        <td>{{ $inventory->max_level ?? '-' }}</td>
                                        <td>
                                            @if($inventory->quantity <= $inventory->reorder_level)
                                                <span class="badge bg-warning">Low Stock</span>
                                            @elseif($inventory->max_level && $inventory->quantity >= $inventory->max_level)
                                                <span class="badge bg-info">Max Stock</span>
                                            @else
                                                <span class="badge bg-success">In Stock</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ $inventory->updated_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                            <p class="text-muted">No inventory records found for this material</p>
                            <a href="{{ route('materials.adjust-inventory.form', $material) }}" class="btn btn-sm btn-outline-secondary mt-2">
                                <i class="fas fa-plus"></i> Add Inventory
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- BOM Usage Section -->
            @if($material->bomItems->count() > 0)
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-list-alt"></i> BOM Usage</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f8f8;">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($material->bomItems as $bomItem)
                                <tr>
                                    <td>
                                        <a href="{{ route('products.show', $bomItem->billOfMaterial->product) }}" 
                                           style="color: #1a1a1a; text-decoration: none; font-weight: 600;">
                                            {{ $bomItem->billOfMaterial->product->name }}
                                        </a>
                                    </td>
                                    <td>{{ $bomItem->quantity }}</td>
                                    <td>{{ $bomItem->unit }}</td>
                                    <td>{{ number_format($bomItem->unit_cost, 2) }} KWD</td>
                                    <td><strong>{{ number_format($bomItem->total_cost, 2) }} KWD</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.info-item {
    padding: 12px;
    border-radius: 8px;
    background-color: #fafafa;
    transition: all 0.3s ease;
}

.info-item:hover {
    background-color: #f5f5f5;
    transform: translateY(-2px);
}

.info-item label {
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item p {
    font-size: 1rem;
}
</style>
@endsection
