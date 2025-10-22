@extends('layouts.app')

@section('title', 'Warehouse Movements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Movements - {{ $warehouse->name }}</h3>
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
                                    <th>Movement Type</th>
                                    <th>Quantity</th>
                                    <th>Reference</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $movement)
                                <tr>
                                    <td>{{ $movement->id }}</td>
                                    <td>{{ $movement->material->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $movement->movement_type == 'in' ? 'success' : ($movement->movement_type == 'out' ? 'danger' : 'info') }}">
                                            {{ ucfirst($movement->movement_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>
                                        @if($movement->reference_type && $movement->reference_id)
                                            {{ ucfirst($movement->reference_type) }} #{{ $movement->reference_id }}
                                        @else
                                            Manual
                                        @endif
                                    </td>
                                    <td>{{ $movement->createdBy ? $movement->createdBy->name : 'System' }}</td>
                                    <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $movement->notes ?? 'No notes' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No movements found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $movements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
