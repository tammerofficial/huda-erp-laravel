@extends('layouts.app')

@section('title', 'Edit Journal Entry')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Journal Entry #{{ $entry->id }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounting.journal.update', $entry) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="entry_date">Entry Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('entry_date') is-invalid @enderror" id="entry_date" name="entry_date" value="{{ old('entry_date', $entry->entry_date->format('Y-m-d')) }}" required>
                                    @error('entry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $entry->amount) }}" min="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="debit_account">Debit Account <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('debit_account') is-invalid @enderror" id="debit_account" name="debit_account" value="{{ old('debit_account', $entry->debit_account) }}" required>
                                    @error('debit_account')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="credit_account">Credit Account <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('credit_account') is-invalid @enderror" id="credit_account" name="credit_account" value="{{ old('credit_account', $entry->credit_account) }}" required>
                                    @error('credit_account')
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
                                        <option value="order" {{ old('reference_type', $entry->reference_type) == 'order' ? 'selected' : '' }}>Order</option>
                                        <option value="invoice" {{ old('reference_type', $entry->reference_type) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                        <option value="purchase_order" {{ old('reference_type', $entry->reference_type) == 'purchase_order' ? 'selected' : '' }}>Purchase Order</option>
                                        <option value="production_order" {{ old('reference_type', $entry->reference_type) == 'production_order' ? 'selected' : '' }}>Production Order</option>
                                    </select>
                                    @error('reference_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_id">Reference ID</label>
                                    <input type="number" class="form-control @error('reference_id') is-invalid @enderror" id="reference_id" name="reference_id" value="{{ old('reference_id', $entry->reference_id) }}" min="1">
                                    @error('reference_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $entry->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('accounting.journal.index') }}" class="btn btn-secondary">
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
