@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Invoice: {{ $invoice->invoice_number }}</h3>
                    <div>
                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Invoice Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Invoice Number:</strong></td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Number:</strong></td>
                                    <td>{{ $invoice->order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>{{ $invoice->order->customer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice Date:</strong></td>
                                    <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Due Date:</strong></td>
                                    <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : 'warning') }}">
                                            {{ $invoice->status }}
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
                                    <td>{{ number_format($invoice->total_amount, 2) }} KWD</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax Amount:</strong></td>
                                    <td>{{ number_format($invoice->tax_amount, 2) }} KWD</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount Amount:</strong></td>
                                    <td>{{ number_format($invoice->discount_amount, 2) }} KWD</td>
                                </tr>
                                <tr>
                                    <td><strong>Final Amount:</strong></td>
                                    <td><strong>{{ number_format($invoice->final_amount, 2) }} KWD</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $invoice->payment_status == 'paid' ? 'success' : ($invoice->payment_status == 'overdue' ? 'danger' : 'warning') }}">
                                            {{ $invoice->payment_status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ $invoice->payment_method ?? 'Not Set' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($invoice->payment_date)
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Payment Date:</strong> {{ $invoice->payment_date->format('Y-m-d') }}
                        </div>
                    </div>
                    @endif

                    @if($invoice->notes)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <p>{{ $invoice->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
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
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->order->orderItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }} KWD</td>
                                    <td>{{ number_format($item->total_price, 2) }} KWD</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                                    <td><strong>{{ number_format($invoice->total_amount, 2) }} KWD</strong></td>
                                </tr>
                                @if($invoice->tax_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Tax:</strong></td>
                                    <td><strong>{{ number_format($invoice->tax_amount, 2) }} KWD</strong></td>
                                </tr>
                                @endif
                                @if($invoice->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Discount:</strong></td>
                                    <td><strong>-{{ number_format($invoice->discount_amount, 2) }} KWD</strong></td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td><strong>{{ number_format($invoice->final_amount, 2) }} KWD</strong></td>
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
