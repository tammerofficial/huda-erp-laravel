@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Purchase Order: {{ $purchaseOrder->order_number }}</h3>
                    <div>
                        <a href="{{ route('purchases.edit', $purchaseOrder) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Order Number:</strong></td>
                                    <td>{{ $purchaseOrder->order_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Supplier:</strong></td>
                                    <td>{{ $purchaseOrder->supplier->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Date:</strong></td>
                                    <td>{{ $purchaseOrder->order_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Delivery Date:</strong></td>
                                    <td>{{ $purchaseOrder->delivery_date ? $purchaseOrder->delivery_date->format('Y-m-d') : 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $purchaseOrder->status == 'received' ? 'success' : ($purchaseOrder->status == 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ $purchaseOrder->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $purchaseOrder->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ $purchaseOrder->payment_status }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Financial Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td>{{ number_format($purchaseOrder->total_amount, 2) }} KWD</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax Amount:</strong></td>
                                    <td>{{ number_format($purchaseOrder->tax_amount, 2) }} KWD</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount Amount:</strong></td>
                                    <td>{{ number_format($purchaseOrder->discount_amount, 2) }} KWD</td>
                                </tr>
                                <tr>
                                    <td><strong>Final Amount:</strong></td>
                                    <td><strong>{{ number_format($purchaseOrder->final_amount, 2) }} KWD</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Priority:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $purchaseOrder->priority == 'urgent' ? 'danger' : ($purchaseOrder->priority == 'high' ? 'warning' : 'info') }}">
                                            {{ $purchaseOrder->priority }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Terms:</strong></td>
                                    <td>{{ $purchaseOrder->payment_terms ?? 'Not Set' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($purchaseOrder->notes)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <p>{{ $purchaseOrder->notes }}</p>
                        </div>
                    </div>
                    @endif

                    @if($purchaseOrder->shipping_address)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Shipping Address</h5>
                            <p>{{ $purchaseOrder->shipping_address }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Purchase Order Items -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Order Items</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Material</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseOrder->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->material->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }} KWD</td>
                                    <td>{{ number_format($item->total_price, 2) }} KWD</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                                    <td><strong>{{ number_format($purchaseOrder->total_amount, 2) }} KWD</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
