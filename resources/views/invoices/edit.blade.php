@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Edit Invoice: {{ $invoice->invoice_number }}</h3>
                </div>
                <div class="">
                    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('status') border-red-500 @enderror" id="status" name="status" required>
                                        <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                        <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                        <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_status') border-red-500 @enderror" id="payment_status" name="payment_status" required>
                                        <option value="pending" {{ old('payment_status', $invoice->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="partial" {{ old('payment_status', $invoice->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="paid" {{ old('payment_status', $invoice->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="overdue" {{ old('payment_status', $invoice->payment_status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    </select>
                                    @error('payment_status')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_method') border-red-500 @enderror" id="payment_method" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method', $invoice->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payment_method', $invoice->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="card" {{ old('payment_method', $invoice->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="cheque" {{ old('payment_method', $invoice->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_date') border-red-500 @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', $invoice->payment_date ? $invoice->payment_date->format('Y-m-d') : '') }}">
                                    @error('payment_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror" id="notes" name="notes" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                            @error('notes')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('invoices.index') }}" class="btn-secondary">
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
