@extends('layouts.app')

@section('title', 'New User')

@section('content')
<div class="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">New User</h3>
                </div>
                <div class="">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('password') border-red-500 @enderror" id="password" name="password" required>
                                    @error('password')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="">
                                <div class="mb-4">
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-danger">*</span></label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('role') border-red-500 @enderror" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }} ({{ $role->permissions->count() }} permissions)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('phone') border-red-500 @enderror" id="phone" name="phone" value="{{ old('phone') }}">
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
                                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('address') border-red-500 @enderror" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="">
                                <div class="mb-4">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="mr-2 text-sm font-medium text-gray-700" for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                                            Active User
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Information (for production_staff role) -->
                        <div id="employee-info" style="display: none;">
                            <h5>Employee Information</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="">
                                    <div class="mb-4">
                                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('position') border-red-500 @enderror" id="position" name="position" value="{{ old('position') }}">
                                        @error('position')
                                            <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="">
                                    <div class="mb-4">
                                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('department') border-red-500 @enderror" id="department" name="department" value="{{ old('department') }}">
                                        @error('department')
                                            <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save User
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const employeeInfo = document.getElementById('employee-info');
    
    roleSelect.addEventListener('change', function() {
        if (this.value === 'production_staff') {
            employeeInfo.style.display = 'block';
        } else {
            employeeInfo.style.display = 'none';
        }
    });
    
    // Show employee info if role is already selected
    if (roleSelect.value === 'production_staff') {
        employeeInfo.style.display = 'block';
    }
});
</script>
@endsection
