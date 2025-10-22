@extends('layouts.app')

@section('title', 'Journal Entry Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Journal Entry #{{ $entry->id }}</h3>
                    <div>
                        <a href="{{ route('accounting.journal.edit', $entry) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('accounting.journal.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Entry Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Entry Date:</strong></td>
                                    <td>{{ $entry->entry_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Debit Account:</strong></td>
                                    <td>{{ $entry->debit_account }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Credit Account:</strong></td>
                                    <td>{{ $entry->credit_account }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td><strong>{{ number_format($entry->amount, 2) }} KWD</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Created By:</strong></td>
                                    <td>{{ $entry->createdBy ? $entry->createdBy->name : 'System' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Reference Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Reference Type:</strong></td>
                                    <td>{{ $entry->reference_type ? ucfirst($entry->reference_type) : 'Manual Entry' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Reference ID:</strong></td>
                                    <td>{{ $entry->reference_id ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Description</h5>
                            <p>{{ $entry->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
