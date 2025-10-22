@extends('layouts.app')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    @include('components.luxury-details-header', [
        'title' => $product->name,
        'subtitle' => 'SKU: ' . $product->sku,
        'icon' => 'fas fa-box',
        'badge' => ['text' => $product->is_active ? 'Active' : 'Inactive', 'class' => $product->is_active ? 'bg-success' : 'bg-danger'],
        'editRoute' => route('products.edit', $product),
        'backRoute' => route('products.index')
    ])

    <!-- Quick Stats Cards Grid -->
    <div class="info-cards-grid">
        <div class="info-card-item">
            <div class="card-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="card-title">Selling Price</div>
            <div class="card-value">{{ number_format($product->price, 2) }} <small>KWD</small></div>
        </div>

        <div class="info-card-item">
            <div class="card-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <i class="fas fa-coins"></i>
            </div>
            <div class="card-title">Cost</div>
            <div class="card-value">{{ number_format($product->cost ?? 0, 2) }} <small>KWD</small></div>
        </div>

        <div class="info-card-item">
            <div class="card-icon" style="background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="card-title">Stock Quantity</div>
            <div class="card-value">{{ $product->stock_quantity }}</div>
        </div>

        <div class="info-card-item">
            <div class="card-icon" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="card-title">Profit Margin</div>
            <div class="card-value">{{ number_format($product->profit_margin ?? 0, 1) }}<small>%</small></div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-4">
            <!-- Product Image -->
            <div class="luxury-card mb-4">
                <div class="p-4">
                    @if($product->image_url)
                        <img src="{{ Storage::url($product->image_url) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded" 
                             style="width: 100%; object-fit: cover; max-height: 300px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center rounded" 
                             style="height: 300px; background: linear-gradient(135deg, #f8f8f8 0%, #e9e9e9 100%);">
                            <i class="fas fa-image" style="font-size: 4rem; color: #d4af37; opacity: 0.3;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.bom.create', $product) }}" class="btn btn-outline-primary">
                            <i class="fas fa-list-alt"></i> Create BOM
                        </a>
                        <a href="{{ route('products.calculate-cost', $product) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-calculator"></i> Calculate Cost
                        </a>
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                            <i class="fas fa-warehouse"></i> Adjust Stock
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-info-circle"></i> Product Info</h5>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        @include('components.info-item', ['label' => 'Category', 'value' => $product->category ?? 'Not specified'])
                    </div>
                    <div class="mb-3">
                        @include('components.info-item', ['label' => 'Type', 'value' => ucfirst($product->product_type)])
                    </div>
                    <div class="mb-3">
                        @include('components.info-item', ['label' => 'Unit', 'value' => ucfirst($product->unit)])
                    </div>
                    <div class="mb-3">
                        @include('components.info-item', ['label' => 'Weight', 'value' => $product->weight ? $product->weight . ' kg' : 'Not specified'])
                    </div>
                    <div class="mb-3">
                        @include('components.info-item', ['label' => 'Reorder Level', 'value' => $product->reorder_level])
                    </div>
                    @if($product->last_cost_calculation_date)
                    <div>
                        @include('components.info-item', ['label' => 'Last Cost Update', 'value' => $product->last_cost_calculation_date->format('Y-m-d')])
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Description -->
            @if($product->description)
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-align-left"></i> Description</h5>
                </div>
                <div class="p-4">
                    <p class="mb-0">{{ $product->description }}</p>
                </div>
            </div>
            @endif

            <!-- Pricing Details -->
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-money-bill-wave"></i> Pricing & Cost Breakdown</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Selling Price</label>
                                <p class="mb-0 fw-semibold" style="color: #d4af37; font-size: 1.25rem;">
                                    {{ number_format($product->price, 2) }} KWD
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Production Cost</label>
                                <p class="mb-0 fw-semibold">{{ number_format($product->cost ?? 0, 2) }} KWD</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Suggested Price</label>
                                <p class="mb-0 fw-semibold">{{ number_format($product->suggested_price ?? 0, 2) }} KWD</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">Profit Margin</label>
                                <p class="mb-0 fw-semibold">
                                    <span class="badge {{ $product->profit_margin > 30 ? 'bg-success' : 'bg-warning' }}" style="font-size: 1rem;">
                                        {{ number_format($product->profit_margin ?? 0, 1) }}%
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bill of Materials -->
            @if($product->billOfMaterials->count() > 0)
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title mb-0"><i class="fas fa-list-alt"></i> Bill of Materials</h5>
                        <span class="badge bg-primary">{{ $product->billOfMaterials->count() }} BOM(s)</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f8f8;">
                                <tr>
                                    <th>Version</th>
                                    <th>Status</th>
                                    <th>Total Cost</th>
                                    <th>Items</th>
                                    <th>Default</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->billOfMaterials as $bom)
                                <tr>
                                    <td><strong>{{ $bom->version }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $bom->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($bom->status) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($bom->total_cost, 2) }} KWD</td>
                                    <td>{{ $bom->bomItems->count() }} items</td>
                                    <td>
                                        @if($bom->is_default)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('products.bom.show', [$product, $bom]) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Specifications -->
            @if($product->specifications)
            <div class="luxury-card mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="section-title mb-0"><i class="fas fa-cogs"></i> Specifications</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        @foreach($product->specifications as $key => $value)
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                <p class="mb-0 fw-semibold">{{ $value }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Orders -->
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title mb-0"><i class="fas fa-shopping-cart"></i> Recent Orders</h5>
                        <a href="{{ route('orders.index', ['product' => $product->id]) }}" class="btn btn-sm btn-outline-secondary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f8f8;">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Quantity</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" style="color: #1a1a1a; font-weight: 600;">
                                                #{{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>{{ $order->orderItems->where('product_id', $product->id)->first()->quantity ?? 0 }}</td>
                                        <td>{{ $order->order_date->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-2x text-muted mb-2" style="opacity: 0.3;"></i>
                            <p class="text-muted">No orders yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

