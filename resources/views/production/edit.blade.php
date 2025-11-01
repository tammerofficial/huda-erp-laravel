@extends('layouts.app')

@section('title', 'Edit Production Order')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Edit Production Order: {{ $production->production_number }}</h3>
                </div>
                <div class="">
                    <form action="{{ route('productions.update', $production) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('status') border-red-500 @enderror" id="status" name="status" required>
                                        <option value="pending" {{ old('status', $production->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in-progress" {{ old('status', $production->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status', $production->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $production->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="on-hold" {{ old('status', $production->status) == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                    </select>
                                    @error('status')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('priority') border-red-500 @enderror" id="priority" name="priority" required>
                                        <option value="low" {{ old('priority', $production->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="normal" {{ old('priority', $production->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('priority', $production->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority', $production->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('start_date') border-red-500 @enderror" id="start_date" name="start_date" value="{{ old('start_date', $production->start_date ? $production->start_date->format('Y-m-d') : '') }}">
                                    @error('start_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('end_date') border-red-500 @enderror" id="end_date" name="end_date" value="{{ old('end_date', $production->end_date ? $production->end_date->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost</label>
                                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('estimated_cost') border-red-500 @enderror" id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $production->estimated_cost) }}">
                                    @error('estimated_cost')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="actual_cost" class="block text-sm font-medium text-gray-700 mb-2">Actual Cost</label>
                                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('actual_cost') border-red-500 @enderror" id="actual_cost" name="actual_cost" value="{{ old('actual_cost', $production->actual_cost) }}">
                                    @error('actual_cost')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror" id="notes" name="notes" rows="3">{{ old('notes', $production->notes) }}</textarea>
                            @error('notes')
                                <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('productions.index') }}" class="btn-secondary">
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
