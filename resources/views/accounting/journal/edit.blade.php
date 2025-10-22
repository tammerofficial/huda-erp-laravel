@extends('layouts.app')

@section('title', 'Edit Journal Entry')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Edit Journal Entry #{{ $entry->id }}</h3>
                </div>
                <div class="">
                    <form action="{{ route('accounting.journal.update', $entry) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="entry_date" class="block text-sm font-medium text-gray-700 mb-2">Entry Date <span class="text-danger">*</span></label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('entry_date') border-red-500 @enderror" id="entry_date" name="entry_date" value="{{ old('entry_date', $entry->entry_date->format('Y-m-d')) }}" required>
                                    @error('entry_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('amount') border-red-500 @enderror" id="amount" name="amount" value="{{ old('amount', $entry->amount) }}" min="0" required>
                                    @error('amount')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="debit_account" class="block text-sm font-medium text-gray-700 mb-2">Debit Account <span class="text-danger">*</span></label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('debit_account') border-red-500 @enderror" id="debit_account" name="debit_account" value="{{ old('debit_account', $entry->debit_account) }}" required>
                                    @error('debit_account')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="credit_account" class="block text-sm font-medium text-gray-700 mb-2">Credit Account <span class="text-danger">*</span></label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('credit_account') border-red-500 @enderror" id="credit_account" name="credit_account" value="{{ old('credit_account', $entry->credit_account) }}" required>
                                    @error('credit_account')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="reference_type" class="block text-sm font-medium text-gray-700 mb-2">Reference Type</label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('reference_type') border-red-500 @enderror" id="reference_type" name="reference_type">
                                        <option value="">Select Reference Type</option>
                                        <option value="order" {{ old('reference_type', $entry->reference_type) == 'order' ? 'selected' : '' }}>Order</option>
                                        <option value="invoice" {{ old('reference_type', $entry->reference_type) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                        <option value="purchase_order" {{ old('reference_type', $entry->reference_type) == 'purchase_order' ? 'selected' : '' }}>Purchase Order</option>
                                        <option value="production_order" {{ old('reference_type', $entry->reference_type) == 'production_order' ? 'selected' : '' }}>Production Order</option>
                                    </select>
                                    @error('reference_type')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="reference_id" class="block text-sm font-medium text-gray-700 mb-2">Reference ID</label>
                                    <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('reference_id') border-red-500 @enderror" id="reference_id" name="reference_id" value="{{ old('reference_id', $entry->reference_id) }}" min="1">
                                    @error('reference_id')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-danger">*</span></label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('description') border-red-500 @enderror" id="description" name="description" rows="3" required>{{ old('description', $entry->description) }}</textarea>
                            @error('description')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('accounting.journal.index') }}" class="btn-secondary">
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
