@extends('layouts.app')

@section('title', 'Low Stock Items')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Low Stock Items</h3>
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
                                    <th>Warehouse</th>
                                    <th>Current Quantity</th>
                                    <th>Reorder Level</th>
                                    <th>Max Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockItems as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->material->name }}</td>
                                    <td>{{ $item->material->sku }}</td>
                                    <td>{{ $item->warehouse->name }}</td>
                                    <td>
                                        <span class="badge {{ $item->quantity == 0 ? 'badge-danger' : 'badge-warning' }}">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td>{{ $item->reorder_level }}</td>
                                    <td>{{ $item->max_level ?? 'Not Set' }}</td>
                                    <td>
                                        @if($item->quantity == 0)
                                            <span class="badge badge-danger">Out of Stock</span>
                                        @else
                                            <span class="badge badge-warning">Low Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('materials.show', $item->material) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View Material
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No low stock items found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
