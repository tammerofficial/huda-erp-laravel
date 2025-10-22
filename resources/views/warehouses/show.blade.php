@extends('layouts.app')

@section('title', 'Warehouse Details')
@section('page-title', 'Warehouse Details')

@section('content')
<div class="container-fluid">
    <!-- Header Card -->
    <div class="luxury-card mb-4">
        <div class="p-4 border-bottom" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1" style="color: #d4af37; font-weight: 600;">
                        <i class="fas fa-warehouse"></i> {{ $warehouse->name }}
                    </h3>
                    <p class="mb-0" style="color: #ffffff; opacity: 0.9;">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $warehouse->location }}
                        @if($warehouse->is_active)
                            <span class="badge bg-success ms-2">Active</span>
                        @else
                            <span class="badge bg-danger ms-2">Inactive</span>
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Stats -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-chart-pie"></i> Inventory Summary</h5>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Items</span>
                            <strong style="color: #d4af37; font-size: 1.5rem;">{{ $totalItems }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Low Stock Items</span>
                            <strong class="badge bg-warning">{{ $lowStockItems }}</strong>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Out of Stock</span>
                            <strong class="badge bg-danger">{{ $outOfStock }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warehouse Info -->
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-info-circle"></i> Warehouse Info</h5>
                </div>
                <div class="p-4">
                    <div class="info-item mb-3">
                        <label class="text-muted mb-1">Capacity</label>
                        <p class="mb-0 fw-semibold">{{ number_format($warehouse->capacity) }} units</p>
                    </div>
                    @if($warehouse->manager)
                    <div class="info-item mb-3">
                        <label class="text-muted mb-1">Manager</label>
                        <p class="mb-0 fw-semibold">{{ $warehouse->manager }}</p>
                    </div>
                    @endif
                    <div class="info-item mb-3">
                        <label class="text-muted mb-1">Created</label>
                        <p class="mb-0 text-muted">{{ $warehouse->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @if($warehouse->notes)
                    <div class="info-item">
                        <label class="text-muted mb-1">Notes</label>
                        <p class="mb-0">{{ $warehouse->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Inventory Details -->
        <div class="col-lg-8">
            <!-- Material Inventory -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title mb-0"><i class="fas fa-boxes"></i> Material Inventory</h5>
                        <a href="{{ route('warehouses.inventory', $warehouse) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye"></i> View All
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    @if($materialInventory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f8f8;">
                                    <tr>
                                        <th>Material</th>
                                        <th>SKU</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Reorder Level</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materialInventory as $inventory)
                                    <tr>
                                        <td>
                                            <a href="{{ route('materials.show', $inventory->material) }}" 
                                               style="color: #1a1a1a; text-decoration: none; font-weight: 600;">
                                                {{ $inventory->material->name }}
                                            </a>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $inventory->material->sku }}</span></td>
                                        <td><strong>{{ $inventory->quantity }}</strong></td>
                                        <td>{{ $inventory->material->unit }}</td>
                                        <td>{{ $inventory->reorder_level }}</td>
                                        <td>
                                            @if($inventory->quantity == 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($inventory->quantity <= $inventory->reorder_level)
                                                <span class="badge bg-warning">Low Stock</span>
                                            @else
                                                <span class="badge bg-success">In Stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                            <p class="text-muted">No inventory items found</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Movements -->
            @if($recentMovements->count() > 0)
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title mb-0"><i class="fas fa-exchange-alt"></i> Recent Movements</h5>
                        <a href="{{ route('warehouses.movements', $warehouse) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-history"></i> View All
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f8f8;">
                                <tr>
                                    <th>Material</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements as $movement)
                                <tr>
                                    <td>{{ $movement->material->name }}</td>
                                    <td>
                                        @if($movement->movement_type == 'in')
                                            <span class="badge bg-success"><i class="fas fa-arrow-down"></i> In</span>
                                        @elseif($movement->movement_type == 'out')
                                            <span class="badge bg-danger"><i class="fas fa-arrow-up"></i> Out</span>
                                        @else
                                            <span class="badge bg-info"><i class="fas fa-sync"></i> {{ ucfirst($movement->movement_type) }}</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $movement->quantity }}</strong></td>
                                    <td class="text-muted">{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $movement->createdBy->name ?? 'System' }}</td>
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
