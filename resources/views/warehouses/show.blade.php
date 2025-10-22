@extends('layouts.app')

@section('title', 'Warehouse Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Warehouse: {{ $warehouse->name }}</h3>
                    <div>
                        <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Warehouse Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $warehouse->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Location:</strong></td>
                                    <td>{{ $warehouse->location }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Capacity:</strong></td>
                                    <td>{{ $warehouse->capacity ?? 'Unlimited' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Manager:</strong></td>
                                    <td>{{ $warehouse->manager ? $warehouse->manager->user->name : 'Not Assigned' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $warehouse->is_active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $warehouse->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Inventory Summary</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Items:</strong></td>
                                    <td>{{ $inventories->total() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Low Stock Items:</strong></td>
                                    <td>
                                        <span class="badge badge-warning">
                                            {{ $inventories->where('quantity', '<=', 'reorder_level')->count() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Out of Stock:</strong></td>
                                    <td>
                                        <span class="badge badge-danger">
                                            {{ $inventories->where('quantity', 0)->count() }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($warehouse->notes)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <p>{{ $warehouse->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Inventory -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Inventory</h4>
                </div>
                <div class="card-body">
                    @if($inventories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>SKU</th>
                                        <th>Quantity</th>
                                        <th>Reorder Level</th>
                                        <th>Max Level</th>
                                        <th>Last Updated</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventories as $inventory)
                                    <tr>
                                        <td>{{ $inventory->material->name }}</td>
                                        <td>{{ $inventory->material->sku }}</td>
                                        <td>
                                            <span class="badge {{ $inventory->quantity <= $inventory->reorder_level ? 'badge-warning' : 'badge-success' }}">
                                                {{ $inventory->quantity }}
                                            </span>
                                        </td>
                                        <td>{{ $inventory->reorder_level }}</td>
                                        <td>{{ $inventory->max_level ?? 'Not Set' }}</td>
                                        <td>{{ $inventory->last_updated ? $inventory->last_updated->format('Y-m-d H:i') : 'Never' }}</td>
                                        <td>
                                            @if($inventory->quantity == 0)
                                                <span class="badge badge-danger">Out of Stock</span>
                                            @elseif($inventory->quantity <= $inventory->reorder_level)
                                                <span class="badge badge-warning">Low Stock</span>
                                            @else
                                                <span class="badge badge-success">In Stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $inventories->links() }}
                        </div>
                    @else
                        <p class="text-center text-muted">No inventory items found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
