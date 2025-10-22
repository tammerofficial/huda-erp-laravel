@extends('layouts.app')

@section('title', 'Journal Entries')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Journal Entries</h3>
                    <a href="{{ route('accounting.journal.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Entry
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Entry Date</th>
                                    <th>Description</th>
                                    <th>Debit Account</th>
                                    <th>Credit Account</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $entry)
                                <tr>
                                    <td>{{ $entry->id }}</td>
                                    <td>{{ $entry->entry_date->format('Y-m-d') }}</td>
                                    <td>{{ Str::limit($entry->description, 50) }}</td>
                                    <td>{{ $entry->debit_account }}</td>
                                    <td>{{ $entry->credit_account }}</td>
                                    <td>{{ number_format($entry->amount, 2) }} KWD</td>
                                    <td>
                                        @if($entry->reference_type && $entry->reference_id)
                                            {{ ucfirst($entry->reference_type) }} #{{ $entry->reference_id }}
                                        @else
                                            Manual
                                        @endif
                                    </td>
                                    <td>{{ $entry->createdBy ? $entry->createdBy->name : 'System' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('accounting.journal.show', $entry) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('accounting.journal.edit', $entry) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('accounting.journal.destroy', $entry) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this entry?')">
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
                                    <td colspan="9" class="text-center">No journal entries found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $entries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
