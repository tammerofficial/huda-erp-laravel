@extends('layouts.app')

@section('title', 'New Invoice')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">New Invoice</h3>
                </div>
                <div class="">
                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">Order <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('order_id') border-red-500 @enderror" id="order_id" name="order_id" required>
                                        <option value="">Select Order</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                {{ $order->order_number }} - {{ $order->customer->name }} ({{ number_format($order->final_amount, 2) }} KWD)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Invoice Date <span class="text-danger">*</span></label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('invoice_date') border-red-500 @enderror" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                    @error('invoice_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('due_date') border-red-500 @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                                    @error('due_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">Tax Amount</label>
                                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('tax_amount') border-red-500 @enderror" id="tax_amount" name="tax_amount" value="{{ old('tax_amount', 0) }}">
                                    @error('tax_amount')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">Discount Amount</label>
                                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('discount_amount') border-red-500 @enderror" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', 0) }}">
                                    @error('discount_amount')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_method') border-red-500 @enderror" id="payment_method" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Invoice
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
