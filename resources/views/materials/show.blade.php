@extends('layouts.app')

@section('title', 'Material Details')
@section('page-title', 'Material Details')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="card mb-6" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
        <div class="card-body text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2" style="color: #d4af37;">
                        <i class="fas fa-cube mr-3"></i>{{ $material->name }}
                    </h1>
                    <div class="flex items-center space-x-4">
                        <span class="badge badge-info">{{ $material->sku }}</span>
                        @if($material->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('materials.edit', $material) }}" class="btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('materials.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Image & Quick Stats -->
        <div class="lg:col-span-1">
            <!-- Material Image -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-image mr-2" style="color: #d4af37;"></i>
                        Material Image
                    </h3>
                </div>
                <div class="card-body">
                    @if($material->image_url)
                        <img src="{{ Storage::url($material->image_url) }}" alt="{{ $material->name }}" 
                             class="w-full rounded-lg" style="height: 300px; object-fit: cover;">
                    @else
                        <div class="flex items-center justify-center rounded-lg" 
                             style="height: 300px; background: linear-gradient(135deg, #f8f8f8 0%, #e9e9e9 100%);">
                            <i class="fas fa-image text-6xl" style="color: #d4af37; opacity: 0.3;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-chart-bar mr-2" style="color: #d4af37;"></i>
                        Quick Stats
                    </h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-title">Unit Cost</div>
                            <div class="card-value" style="color: #d4af37;">{{ number_format($material->unit_cost, 2) }} KWD</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-ruler"></i>
                            </div>
                            <div class="card-title">Unit</div>
                            <div class="card-value">{{ ucfirst($material->unit) }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="card-title">Reorder Level</div>
                            <div class="card-value">{{ $material->reorder_level }}</div>
                        </div>

                        @if($material->max_stock)
                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="card-title">Max Stock</div>
                            <div class="card-value">{{ $material->max_stock }}</div>
                        </div>
                        @endif

                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="card-title">Total Inventory</div>
                            <div class="card-value" style="color: #10b981;">
                                {{ $material->inventories->sum('quantity') }} {{ $material->unit }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details & Tables -->
        <div class="lg:col-span-2">
            
            <!-- Basic Information Grid -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-info-circle mr-2" style="color: #d4af37;"></i>
                        Basic Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="info-card-item">
                            <div class="card-title">Material Name</div>
                            <div class="card-value">{{ $material->name }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-title">SKU</div>
                            <div class="card-value">{{ $material->sku }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-title">Category</div>
                            <div class="card-value">{{ $material->category ? ucfirst($material->category) : 'Not specified' }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-title">Supplier</div>
                            <div class="card-value">
                                @if($material->supplier)
                                    <a href="{{ route('suppliers.show', $material->supplier) }}" 
                                       style="color: #d4af37; text-decoration: none; font-weight: 600;">
                                        {{ $material->supplier->name }}
                                    </a>
                                @else
                                    Not specified
                                @endif
                            </div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-title">Color</div>
                            <div class="card-value">{{ $material->color ?? 'Not specified' }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-title">Size</div>
                            <div class="card-value">{{ $material->size ?? 'Not specified' }}</div>
                        </div>

                        <div class="info-card-item md:col-span-2">
                            <div class="card-title">Description</div>
                            <div class="card-value">{{ $material->description ?? 'No description available' }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-title">Created At</div>
                            <div class="card-value" style="color: #666;">{{ $material->created_at->format('Y-m-d H:i') }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-title">Last Updated</div>
                            <div class="card-value" style="color: #666;">{{ $material->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Section -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-warehouse mr-2" style="color: #d4af37;"></i>
                        Inventory by Warehouse
                    </h3>
                </div>
                <div class="card-body">
                    @if($material->inventories->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th class="pb-3">Warehouse</th>
                                        <th class="pb-3">Quantity</th>
                                        <th class="pb-3">Reorder Level</th>
                                        <th class="pb-3">Max Level</th>
                                        <th class="pb-3">Status</th>
                                        <th class="pb-3">Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($material->inventories as $inventory)
                                    <tr>
                                        <td class="py-3">
                                            <i class="fas fa-warehouse mr-2" style="color: #666;"></i>
                                            {{ $inventory->warehouse->name }}
                                        </td>
                                        <td class="py-3">
                                            <strong>{{ $inventory->quantity }} {{ $material->unit }}</strong>
                                        </td>
                                        <td class="py-3">{{ $inventory->reorder_level }}</td>
                                        <td class="py-3">{{ $inventory->max_level ?? '-' }}</td>
                                        <td class="py-3">
                                            @if($inventory->quantity <= $inventory->reorder_level)
                                                <span class="badge badge-warning">Low Stock</span>
                                            @elseif($inventory->max_level && $inventory->quantity >= $inventory->max_level)
                                                <span class="badge badge-info">Max Stock</span>
                                            @else
                                                <span class="badge badge-success">In Stock</span>
                                            @endif
                                        </td>
                                        <td class="py-3" style="color: #666;">{{ $inventory->updated_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-box-open text-6xl mb-4" style="color: #666; opacity: 0.3;"></i>
                            <p style="color: #666; margin-bottom: 1rem;">No inventory records found for this material</p>
                            <a href="{{ route('materials.adjust-inventory.form', $material) }}" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i>Add Inventory
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- BOM Usage Section -->
            @if($material->bomItems->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-list-alt mr-2" style="color: #d4af37;"></i>
                        BOM Usage
                    </h3>
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="pb-3">Product</th>
                                    <th class="pb-3">Quantity</th>
                                    <th class="pb-3">Unit</th>
                                    <th class="pb-3">Unit Cost</th>
                                    <th class="pb-3">Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($material->bomItems as $bomItem)
                                <tr>
                                    <td class="py-3">
                                        <a href="{{ route('products.show', $bomItem->billOfMaterial->product) }}" 
                                           style="color: #1a1a1a; text-decoration: none; font-weight: 600;">
                                            {{ $bomItem->billOfMaterial->product->name }}
                                        </a>
                                    </td>
                                    <td class="py-3">{{ $bomItem->quantity }}</td>
                                    <td class="py-3">{{ $bomItem->unit }}</td>
                                    <td class="py-3">{{ number_format($bomItem->unit_cost, 2) }} KWD</td>
                                    <td class="py-3">
                                        <strong style="color: #d4af37;">{{ number_format($bomItem->total_cost, 2) }} KWD</strong>
                                    </td>
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
/* Enhanced Info Card Items for Grid Layout */
.info-card-item {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e5e5e5;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.info-card-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.2);
    border-color: #d4af37;
}

.info-card-item .card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: white;
    font-size: 1.5rem;
}

.info-card-item .card-title {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.info-card-item .card-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a1a;
}

/* Grid Layout Enhancements */
.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

.grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.lg\:col-span-1 {
    grid-column: span 1 / span 1;
}

.lg\:col-span-2 {
    grid-column: span 2 / span 2;
}

@media (min-width: 1024px) {
    .lg\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (min-width: 768px) {
    .md\:col-span-2 {
        grid-column: span 2 / span 2;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .lg\:col-span-1,
    .lg\:col-span-2 {
        grid-column: span 1 / span 1;
    }
}
</style>
@endsection