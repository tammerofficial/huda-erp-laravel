@extends('layouts.app')

@section('title', 'Warehouse Inventory')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Inventory - {{ $warehouse->name }}</h3>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Warehouses
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Material</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Reorder Level</th>
                                    <th>Max Level</th>
                                    <th>Last Updated</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventories as $inventory)
                                <tr>
                                    <td>{{ $inventory->id }}</td>
                                    <td>{{ $inventory->material->name }}</td>
                                    <td>{{ $inventory->material->sku }}</td>
                                    <td>{{ $inventory->material->category ?? 'Not Set' }}</td>
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
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No inventory items found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $inventories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
