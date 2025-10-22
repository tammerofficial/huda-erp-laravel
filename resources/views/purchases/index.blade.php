@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Purchase Orders</h3>
                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Purchase Order
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Supplier</th>
                                    <th>Order Date</th>
                                    <th>Delivery Date</th>
                                    <th>Total Amount</th>
                                    <th>Final Amount</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Priority</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseOrders as $purchaseOrder)
                                <tr>
                                    <td>{{ $purchaseOrder->id }}</td>
                                    <td>{{ $purchaseOrder->order_number }}</td>
                                    <td>{{ $purchaseOrder->supplier->name }}</td>
                                    <td>{{ $purchaseOrder->order_date->format('Y-m-d') }}</td>
                                    <td>{{ $purchaseOrder->delivery_date ? $purchaseOrder->delivery_date->format('Y-m-d') : 'Not Set' }}</td>
                                    <td>{{ number_format($purchaseOrder->total_amount, 2) }} KWD</td>
                                    <td>{{ number_format($purchaseOrder->final_amount, 2) }} KWD</td>
                                    <td>
                                        <span class="badge badge-{{ $purchaseOrder->status == 'received' ? 'success' : ($purchaseOrder->status == 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ $purchaseOrder->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $purchaseOrder->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ $purchaseOrder->payment_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $purchaseOrder->priority == 'urgent' ? 'danger' : ($purchaseOrder->priority == 'high' ? 'warning' : 'info') }}">
                                            {{ $purchaseOrder->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('purchases.show', $purchaseOrder) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('purchases.edit', $purchaseOrder) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($purchaseOrder->status == 'sent')
                                                <form action="{{ route('purchases.receive', $purchaseOrder) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this order as received?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i> Receive
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('purchases.destroy', $purchaseOrder) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this purchase order?')">
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
                                    <td colspan="11" class="text-center">No purchase orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $purchaseOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
