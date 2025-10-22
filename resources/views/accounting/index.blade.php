@extends('layouts.app')

@section('title', 'Accounting Entries')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Accounting Entries</h3>
                    <a href="{{ route('accounting.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Entry
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Reference</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accountings as $accounting)
                                <tr>
                                    <td>{{ $accounting->id }}</td>
                                    <td>{{ $accounting->date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $accounting->type == 'revenue' ? 'success' : ($accounting->type == 'expense' ? 'danger' : 'info') }}">
                                            {{ ucfirst($accounting->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $accounting->category }}</td>
                                    <td>{{ number_format($accounting->amount, 2) }} KWD</td>
                                    <td>{{ Str::limit($accounting->description, 50) }}</td>
                                    <td>
                                        @if($accounting->reference_type && $accounting->reference_id)
                                            {{ ucfirst($accounting->reference_type) }} #{{ $accounting->reference_id }}
                                        @else
                                            Manual
                                        @endif
                                    </td>
                                    <td>{{ $accounting->createdBy ? $accounting->createdBy->name : 'System' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('accounting.show', $accounting) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('accounting.edit', $accounting) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('accounting.destroy', $accounting) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this entry?')">
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
                                    <td colspan="9" class="text-center">No accounting entries found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $accountings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
