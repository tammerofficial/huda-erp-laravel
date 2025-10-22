@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Invoices</h3>
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Invoice
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice Number</th>
                                    <th>Customer</th>
                                    <th>Order Number</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Total Amount</th>
                                    <th>Final Amount</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->id }}</td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->order->customer->name }}</td>
                                    <td>{{ $invoice->order->order_number }}</td>
                                    <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                    <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                    <td>{{ number_format($invoice->total_amount, 2) }} KWD</td>
                                    <td>{{ number_format($invoice->final_amount, 2) }} KWD</td>
                                    <td>
                                        <span class="badge badge-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : 'warning') }}">
                                            {{ $invoice->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $invoice->payment_status == 'paid' ? 'success' : ($invoice->payment_status == 'overdue' ? 'danger' : 'warning') }}">
                                            {{ $invoice->payment_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($invoice->status == 'draft')
                                                <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Send this invoice?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-paper-plane"></i> Send
                                                    </button>
                                                </form>
                                            @endif
                                            @if($invoice->status == 'sent' && $invoice->payment_status != 'paid')
                                                <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this invoice as paid?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-check"></i> Mark Paid
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this invoice?')">
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
                                    <td colspan="11" class="text-center">No invoices found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
