@extends('layouts.app')

@section('title', 'Accounting Entry Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Accounting Entry #{{ $accounting->id }}</h3>
                    <div>
                        <a href="{{ route('accounting.edit', $accounting) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('accounting.index') }}" class="btn btn-secondary">
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
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $accounting->date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $accounting->type == 'revenue' ? 'success' : ($accounting->type == 'expense' ? 'danger' : 'info') }}">
                                            {{ ucfirst($accounting->type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ $accounting->category }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td><strong>{{ number_format($accounting->amount, 2) }} KWD</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Created By:</strong></td>
                                    <td>{{ $accounting->createdBy ? $accounting->createdBy->name : 'System' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ $accounting->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Reference Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Reference Type:</strong></td>
                                    <td>{{ $accounting->reference_type ? ucfirst($accounting->reference_type) : 'Manual Entry' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Reference ID:</strong></td>
                                    <td>{{ $accounting->reference_id ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Description</h5>
                            <p>{{ $accounting->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
