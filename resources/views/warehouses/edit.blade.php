@extends('layouts.app')

@section('title', 'Edit Warehouse')
@section('page-title', 'Edit Warehouse')

@section('content')
<div>
    <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Warehouse: {{ $warehouse->name }}</h2>
                    <p class="text-gray-600 mt-1">Update warehouse information and settings</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('warehouses.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Warehouse Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Warehouse Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $warehouse->name) }}"
                           required
                           placeholder="e.g., Main Warehouse, Storage Unit A..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="location" 
                           value="{{ old('location', $warehouse->location) }}"
                           required
                           placeholder="e.g., Shuwaikh Industrial Area, Kuwait..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('location') border-red-500 @enderror">
                    @error('location')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Storage Capacity (sq m)
                    </label>
                    <input type="number" 
                           name="capacity" 
                           value="{{ old('capacity', $warehouse->capacity) }}"
                           min="0"
                           step="0.01"
                           placeholder="Total storage space in square meters"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('capacity') border-red-500 @enderror">
                    @error('capacity')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Warehouse Manager
                    </label>
                    <select name="manager_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('manager_id') border-red-500 @enderror">
                        <option value="">Select Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $warehouse->manager_id) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Additional Information</h3>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" 
                              rows="4"
                              placeholder="Additional notes about this warehouse, special storage conditions, access information..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $warehouse->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active"
                           value="1"
                           {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded">
                    <label for="is_active" class="mr-2 text-sm font-medium text-gray-700">
                        Active Warehouse
                    </label>
                    <p class="text-xs text-gray-500">(Inactive warehouses won't appear in selections)</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('warehouses.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
