@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Supplier: {{ $supplier->name }}</h3>
                    <div>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Supplier Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Contact Person:</strong></td>
                                    <td>{{ $supplier->contact_person ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $supplier->email ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $supplier->phone ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $supplier->address ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City:</strong></td>
                                    <td>{{ $supplier->city ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Country:</strong></td>
                                    <td>{{ $supplier->country ?? 'Not Set' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Business Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tax Number:</strong></td>
                                    <td>{{ $supplier->tax_number ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Terms:</strong></td>
                                    <td>{{ $supplier->payment_terms ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Credit Limit:</strong></td>
                                    <td>{{ $supplier->credit_limit ? number_format($supplier->credit_limit, 2) . ' KWD' : 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Current Balance:</strong></td>
                                    <td>{{ $supplier->current_balance ? number_format($supplier->current_balance, 2) . ' KWD' : 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Supplier Type:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($supplier->supplier_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $supplier->is_active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $supplier->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($supplier->notes)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <p>{{ $supplier->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Materials -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Supplied Materials</h4>
                </div>
                <div class="card-body">
                    @if($supplier->materials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Unit Cost</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->materials as $material)
                                    <tr>
                                        <td>{{ $material->name }}</td>
                                        <td>{{ $material->sku }}</td>
                                        <td>{{ $material->category ?? 'Not Set' }}</td>
                                        <td>{{ number_format($material->unit_cost, 2) }} KWD</td>
                                        <td>
                                            <span class="badge {{ $material->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $material->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No materials supplied by this supplier</p>
                    @endif
                </div>
            </div>

            <!-- Purchase Orders -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Purchase Orders</h4>
                </div>
                <div class="card-body">
                    @if($supplier->purchaseOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>Order Date</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->purchaseOrders as $purchaseOrder)
                                    <tr>
                                        <td>{{ $purchaseOrder->order_number }}</td>
                                        <td>{{ $purchaseOrder->order_date->format('Y-m-d') }}</td>
                                        <td>{{ number_format($purchaseOrder->final_amount, 2) }} KWD</td>
                                        <td>
                                            <span class="badge badge-{{ $purchaseOrder->status == 'received' ? 'success' : 'warning' }}">
                                                {{ $purchaseOrder->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $purchaseOrder->payment_status == 'paid' ? 'success' : 'warning' }}">
                                                {{ $purchaseOrder->payment_status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No purchase orders found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection