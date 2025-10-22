@extends('layouts.app')

@section('title', 'New Accounting Entry')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">New Accounting Entry</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounting.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="revenue" {{ old('type') == 'revenue' ? 'selected' : '' }}>Revenue</option>
                                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                                        <option value="asset" {{ old('type') == 'asset' ? 'selected' : '' }}>Asset</option>
                                        <option value="liability" {{ old('type') == 'liability' ? 'selected' : '' }}>Liability</option>
                                        <option value="equity" {{ old('type') == 'equity' ? 'selected' : '' }}>Equity</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" required>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" min="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_type">Reference Type</label>
                                    <select class="form-control @error('reference_type') is-invalid @enderror" id="reference_type" name="reference_type">
                                        <option value="">Select Reference Type</option>
                                        <option value="order" {{ old('reference_type') == 'order' ? 'selected' : '' }}>Order</option>
                                        <option value="invoice" {{ old('reference_type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                        <option value="purchase_order" {{ old('reference_type') == 'purchase_order' ? 'selected' : '' }}>Purchase Order</option>
                                        <option value="production_order" {{ old('reference_type') == 'production_order' ? 'selected' : '' }}>Production Order</option>
                                    </select>
                                    @error('reference_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_id">Reference ID</label>
                                    <input type="number" class="form-control @error('reference_id') is-invalid @enderror" id="reference_id" name="reference_id" value="{{ old('reference_id') }}" min="1">
                                    @error('reference_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Entry
                            </button>
                            <a href="{{ route('accounting.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
