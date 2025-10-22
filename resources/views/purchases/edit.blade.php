@extends('layouts.app')

@section('title', 'Edit Purchase Order')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Edit Purchase Order: {{ $purchaseOrder->order_number }}</h3>
                </div>
                <div class="">
                    <form action="{{ route('purchases.update', $purchaseOrder) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('status') border-red-500 @enderror" id="status" name="status" required>
                                        <option value="pending" {{ old('status', $purchaseOrder->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $purchaseOrder->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="sent" {{ old('status', $purchaseOrder->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                        <option value="received" {{ old('status', $purchaseOrder->status) == 'received' ? 'selected' : '' }}>Received</option>
                                        <option value="cancelled" {{ old('status', $purchaseOrder->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                        <option value="pending" {{ old('payment_status', $purchaseOrder->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="partial" {{ old('payment_status', $purchaseOrder->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="paid" {{ old('payment_status', $purchaseOrder->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
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
                                    <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('delivery_date') border-red-500 @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $purchaseOrder->delivery_date ? $purchaseOrder->delivery_date->format('Y-m-d') : '') }}">
                                    @error('delivery_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('priority') border-red-500 @enderror" id="priority" name="priority">
                                        <option value="low" {{ old('priority', $purchaseOrder->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="normal" {{ old('priority', $purchaseOrder->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('priority', $purchaseOrder->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority', $purchaseOrder->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_terms') border-red-500 @enderror" id="payment_terms" name="payment_terms" value="{{ old('payment_terms', $purchaseOrder->payment_terms) }}">
                                    @error('payment_terms')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_address') border-red-500 @enderror" id="shipping_address" name="shipping_address" rows="2">{{ old('shipping_address', $purchaseOrder->shipping_address) }}</textarea>
                                    @error('shipping_address')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror" id="notes" name="notes" rows="3">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                            @error('notes')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('purchases.index') }}" class="btn-secondary">
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
