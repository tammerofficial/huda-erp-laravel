@extends('layouts.app')

@section('title', 'Low Stock Materials')
@section('page-title', 'Low Stock Materials')

@section('content')
<div class="container-fluid">
    <!-- Header Card -->
    <div class="luxury-card mb-4">
        <div class="p-4" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 text-white">
                        <i class="fas fa-exclamation-triangle"></i> Low Stock Alert
                    </h3>
                    <p class="mb-0 text-white opacity-75">
                        Materials that have reached or fallen below minimum stock levels
                    </p>
                    <p class="mb-0 text-white mt-2">
                        <i class="fas fa-robot"></i> <strong>Auto-purchase orders will be created automatically at 9:00 AM daily</strong>
                    </p>
                </div>
                <div>
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left"></i> Back to Materials
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid #dc3545;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Low Stock</div>
                        <div class="stat-number">{{ $materials->count() }}</div>
                    </div>
                    <div class="stat-icon" style="color: #dc3545;">⚠️</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid #fd7e14;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Auto-Purchase Enabled</div>
                        <div class="stat-number">{{ $materials->where('auto_purchase_enabled', true)->count() }}</div>
                    </div>
                    <div class="stat-icon" style="color: #fd7e14;">🤖</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid #ffc107;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Needs Manual Order</div>
                        <div class="stat-number">{{ $materials->where('auto_purchase_enabled', false)->count() }}</div>
                    </div>
                    <div class="stat-icon" style="color: #ffc107;">📋</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid #6c757d;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Without Supplier</div>
                        <div class="stat-number">{{ $materials->whereNull('supplier_id')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="color: #6c757d;">❌</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Materials Table -->
    <div class="luxury-card">
        <div class="p-4 border-bottom">
            <h5 class="section-title mb-0"><i class="fas fa-list"></i> Low Stock Materials</h5>
        </div>
        <div class="p-4">
            @if($materials->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f8f8;">
                            <tr>
                                <th>Material</th>
                                <th>SKU</th>
                                <th>Current Stock</th>
                                <th>Min Level</th>
                                <th>Shortage</th>
                                <th>Supplier</th>
                                <th>Auto Purchase</th>
                                <th>Purchase Qty</th>
                                <th>Estimated Cost</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $material)
                            @php
                                $currentStock = $material->available_quantity;
                                $minLevel = $material->min_stock_level ?? $material->reorder_level;
                                $shortage = max(0, $minLevel - $currentStock);
                                $estimatedCost = $material->unit_cost * $material->auto_purchase_quantity;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($material->image_url)
                                            <img src="{{ Storage::url($material->image_url) }}" 
                                                 alt="{{ $material->name }}" 
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px; background: #f0f0f0;">
                                                <i class="fas fa-cube text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('materials.show', $material) }}" 
                                               style="color: #1a1a1a; font-weight: 600; text-decoration: none;">
                                                {{ $material->name }}
                                            </a>
                                            @if($material->color)
                                                <div class="text-muted" style="font-size: 0.85rem;">{{ $material->color }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $material->sku }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-danger">
                                        {{ $currentStock }} {{ $material->unit }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $minLevel }} {{ $material->unit }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        -{{ $shortage }} {{ $material->unit }}
                                    </span>
                                </td>
                                <td>
                                    @if($material->supplier)
                                        <a href="{{ route('suppliers.show', $material->supplier) }}" 
                                           style="color: #d4af37; text-decoration: none;">
                                            {{ $material->supplier->name }}
                                        </a>
                                    @else
                                        <span class="text-danger">
                                            <i class="fas fa-exclamation-circle"></i> No Supplier
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($material->auto_purchase_enabled)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Enabled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times"></i> Disabled
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $material->auto_purchase_quantity }} {{ $material->unit }}</strong>
                                </td>
                                <td>
                                    <strong style="color: #d4af37;">
                                        {{ number_format($estimatedCost, 3) }} KWD
                                    </strong>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('materials.adjust-inventory.form', $material) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Adjust Inventory">
                                            <i class="fas fa-sync"></i>
                                        </a>
                                        <a href="{{ route('materials.edit', $material) }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Edit Material">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background-color: #f8f8f8; font-weight: 600;">
                            <tr>
                                <td colspan="8" class="text-end">Total Estimated Cost:</td>
                                <td colspan="2">
                                    <strong style="color: #d4af37; font-size: 1.1rem;">
                                        {{ number_format($materials->sum(function($m) { return $m->unit_cost * $m->auto_purchase_quantity; }), 3) }} KWD
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3" style="opacity: 0.3;"></i>
                    <h4 class="text-muted">All Materials Are In Stock!</h4>
                    <p class="text-muted">No materials have fallen below their minimum stock levels.</p>
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary mt-3">
                        <i class="fas fa-arrow-left"></i> Back to Materials
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e5e5e5;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.3;
}
</style>
@endsection

