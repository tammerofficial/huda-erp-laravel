@extends('layouts.app')

@section('title', 'Edit Accounting Entry')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Edit Accounting Entry #{{ $accounting->id }}</h3>
                </div>
                <div class="">
                    <form action="{{ route('accounting.update', $accounting) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('date') border-red-500 @enderror" id="date" name="date" value="{{ old('date', $accounting->date->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('type') border-red-500 @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="revenue" {{ old('type', $accounting->type) == 'revenue' ? 'selected' : '' }}>Revenue</option>
                                        <option value="expense" {{ old('type', $accounting->type) == 'expense' ? 'selected' : '' }}>Expense</option>
                                        <option value="asset" {{ old('type', $accounting->type) == 'asset' ? 'selected' : '' }}>Asset</option>
                                        <option value="liability" {{ old('type', $accounting->type) == 'liability' ? 'selected' : '' }}>Liability</option>
                                        <option value="equity" {{ old('type', $accounting->type) == 'equity' ? 'selected' : '' }}>Equity</option>
                                    </select>
                                    @error('type')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-danger">*</span></label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('category') border-red-500 @enderror" id="category" name="category" value="{{ old('category', $accounting->category) }}" required>
                                    @error('category')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('amount') border-red-500 @enderror" id="amount" name="amount" value="{{ old('amount', $accounting->amount) }}" min="0" required>
                                    @error('amount')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-danger">*</span></label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('description') border-red-500 @enderror" id="description" name="description" rows="3" required>{{ old('description', $accounting->description) }}</textarea>
                            @error('description')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('accounting.index') }}" class="btn-secondary">
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
