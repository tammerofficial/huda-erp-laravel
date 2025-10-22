@extends('layouts.app')

@section('title', 'Adjust Material Inventory')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Adjust Inventory for: {{ $material->name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('materials.adjust-inventory', $material) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_id">Warehouse <span class="text-danger">*</span></label>
                                    <select class="form-control @error('warehouse_id') is-invalid @enderror" id="warehouse_id" name="warehouse_id" required>
                                        <option value="">Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }} - {{ $warehouse->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="movement_type">Movement Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('movement_type') is-invalid @enderror" id="movement_type" name="movement_type" required>
                                        <option value="">Select Movement Type</option>
                                        <option value="in" {{ old('movement_type') == 'in' ? 'selected' : '' }}>Inbound</option>
                                        <option value="out" {{ old('movement_type') == 'out' ? 'selected' : '' }}>Outbound</option>
                                        <option value="transfer" {{ old('movement_type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="adjustment" {{ old('movement_type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                                    </select>
                                    @error('movement_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <input type="text" class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" value="{{ old('notes') }}">
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Adjust Inventory
                            </button>
                            <a href="{{ route('materials.show', $material) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Inventory -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Current Inventory</h4>
                </div>
                <div class="card-body">
                    @if($material->inventories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Warehouse</th>
                                        <th>Current Quantity</th>
                                        <th>Reorder Level</th>
                                        <th>Max Level</th>
                                        <th>Last Updated</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($material->inventories as $inventory)
                                    <tr>
                                        <td>{{ $inventory->warehouse->name }}</td>
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
                    @else
                        <p class="text-center text-muted">No inventory records found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
