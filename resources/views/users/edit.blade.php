@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Edit User: {{ $user->name }}</h3>
                </div>
                <div class="">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('password') border-red-500 @enderror" id="password" name="password">
                                    <small class="form-text text-muted">Leave blank to keep current password</small>
                                    @error('password')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('role') border-red-500 @enderror" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="accountant" {{ old('role', $user->role) == 'accountant' ? 'selected' : '' }}>Accountant</option>
                                        <option value="production_staff" {{ old('role', $user->role) == 'production_staff' ? 'selected' : '' }}>Production Staff</option>
                                        <option value="purchasing_agent" {{ old('role', $user->role) == 'purchasing_agent' ? 'selected' : '' }}>Purchasing Agent</option>
                                    </select>
                                    @error('role')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('phone') border-red-500 @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('address') border-red-500 @enderror" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <label class="mr-2 text-sm font-medium text-gray-700" for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                                            Active User
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('users.index') }}" class="btn-secondary">
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
