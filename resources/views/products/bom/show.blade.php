@extends('layouts.app')

@section('title', 'BOM Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">BOM: {{ $bom->product->name }} - Version {{ $bom->version }}</h3>
                    <div>
                        <a href="{{ route('products.show', $bom->product) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Product
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>BOM Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Product:</strong></td>
                                    <td>{{ $bom->product->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Version:</strong></td>
                                    <td>{{ $bom->version }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $bom->status == 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($bom->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Cost:</strong></td>
                                    <td><strong>{{ number_format($bom->total_cost, 2) }} KWD</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Default BOM:</strong></td>
                                    <td>
                                        @if($bom->is_default)
                                            <span class="badge badge-primary">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created By:</strong></td>
                                    <td>{{ $bom->createdBy ? $bom->createdBy->name : 'System' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ $bom->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Description</h5>
                            <p>{{ $bom->description ?? 'No description provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOM Items -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">BOM Items</h4>
                </div>
                <div class="card-body">
                    @if($bom->bomItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Material</th>
                                        <th>SKU</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Unit Cost</th>
                                        <th>Total Cost</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bom->bomItems as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->material->name }}</td>
                                        <td>{{ $item->material->sku }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ number_format($item->unit_cost, 2) }} KWD</td>
                                        <td>{{ number_format($item->total_cost, 2) }} KWD</td>
                                        <td>{{ $item->notes ?? 'No notes' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-right"><strong>Total BOM Cost:</strong></td>
                                        <td><strong>{{ number_format($bom->total_cost, 2) }} KWD</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">No items in this BOM</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
