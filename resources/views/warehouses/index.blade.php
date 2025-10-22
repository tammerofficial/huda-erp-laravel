@extends('layouts.app')

@section('title', 'Warehouses')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Warehouses</h3>
                    <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Warehouse
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Capacity</th>
                                    <th>Manager</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $warehouse->id }}</td>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->location }}</td>
                                    <td>{{ $warehouse->capacity ?? 'Unlimited' }}</td>
                                    <td>{{ $warehouse->manager ? $warehouse->manager->user->name : 'Not Assigned' }}</td>
                                    <td>
                                        <span class="badge {{ $warehouse->is_active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('warehouses.show', $warehouse) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('warehouses.inventory', $warehouse) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-boxes"></i> Inventory
                                            </a>
                                            <a href="{{ route('warehouses.movements', $warehouse) }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-exchange-alt"></i> Movements
                                            </a>
                                            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this warehouse?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No warehouses found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $warehouses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
