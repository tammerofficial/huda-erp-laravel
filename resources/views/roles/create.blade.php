@extends('layouts.app')

@section('title', 'Add New User')
@section('page-title', 'Add New User')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="card mb-6" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
        <div class="card-body text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2" style="color: #d4af37;">
                        <i class="fas fa-user-plus mr-3"></i>Add New User
                    </h1>
                    <p class="text-white opacity-90">Create a new system user with roles and permissions</p>
                </div>
                <div>
                    <a href="{{ route('roles.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-user mr-2" style="color: #d4af37;"></i>
                        User Information
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="form-group-enhanced">
                                <label>Full Name <span class="required">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" 
                                       class="form-control" required>
                                @error('name')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group-enhanced">
                                <label>Email Address <span class="required">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" 
                                       class="form-control" required>
                                @error('email')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group-enhanced">
                                <label>Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" 
                                       class="form-control" placeholder="+965 XXXX XXXX">
                                @error('phone')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group-enhanced">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="3" 
                                          placeholder="Full address...">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="form-group-enhanced">
                                <label>Password <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <input type="password" name="password" id="password" 
                                           class="form-control" required>
                                    <i class="fas fa-eye input-icon" onclick="togglePassword('password')"></i>
                                </div>
                                @error('password')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group-enhanced">
                                <label>Confirm Password <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="form-control" required>
                                    <i class="fas fa-eye input-icon" onclick="togglePassword('password_confirmation')"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Roles Section -->
                        <div class="mb-6">
                            <h4 class="text-lg font-bold mb-4" style="color: #1a1a1a;">
                                <i class="fas fa-user-tag mr-2" style="color: #d4af37;"></i>
                                Assign Roles
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($roles as $role)
                                <div class="info-card-item">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                               id="role_{{ $role->id }}" class="form-checkbox mr-3"
                                               {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                        <label for="role_{{ $role->id }}" class="flex-1 cursor-pointer">
                                            <div class="font-semibold" style="color: #1a1a1a;">{{ ucfirst($role->name) }}</div>
                                            <div class="text-sm" style="color: #666;">
                                                {{ $role->permissions->count() }} permissions
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permissions Section -->
                        <div class="mb-6">
                            <h4 class="text-lg font-bold mb-4" style="color: #1a1a1a;">
                                <i class="fas fa-key mr-2" style="color: #d4af37;"></i>
                                Additional Permissions
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($permissions as $permission)
                                <div class="info-card-item">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                               id="permission_{{ $permission->id }}" class="form-checkbox mr-3"
                                               {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                        <label for="permission_{{ $permission->id }}" class="flex-1 cursor-pointer">
                                            <div class="font-semibold text-sm" style="color: #1a1a1a;">
                                                {{ ucfirst(str_replace(['.', '_'], ' ', $permission->name)) }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <div class="info-card-item">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" id="is_active" 
                                           class="form-checkbox mr-3" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label for="is_active" class="flex-1 cursor-pointer">
                                        <div class="font-semibold" style="color: #1a1a1a;">Active User</div>
                                        <div class="text-sm" style="color: #666;">
                                            User can login and access the system
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('roles.index') }}" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar - Help & Info -->
        <div class="lg:col-span-1">
            <!-- Role Information -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-info-circle mr-2" style="color: #d4af37;"></i>
                        Role Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        @foreach($roles as $role)
                        <div class="info-card-item">
                            <div class="card-title">{{ ucfirst($role->name) }}</div>
                            <div class="card-value text-sm" style="color: #666;">
                                {{ $role->permissions->count() }} permissions
                            </div>
                            <div class="text-xs mt-2" style="color: #999;">
                                @foreach($role->permissions->take(3) as $permission)
                                    {{ ucfirst(str_replace(['.', '_'], ' ', $permission->name)) }},
                                @endforeach
                                @if($role->permissions->count() > 3)
                                    +{{ $role->permissions->count() - 3 }} more...
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-shield-alt mr-2" style="color: #d4af37;"></i>
                        Security Tips
                    </h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <div class="text-sm">
                                <strong>Strong Passwords:</strong> Use at least 8 characters with mixed case, numbers, and symbols.
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <div class="text-sm">
                                <strong>Principle of Least Privilege:</strong> Assign only necessary roles and permissions.
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <div class="text-sm">
                                <strong>Regular Reviews:</strong> Periodically review user access and permissions.
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <div class="text-sm">
                                <strong>Active Monitoring:</strong> Monitor user activity and login patterns.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling;
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Auto-generate password suggestion
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password');
    const generateBtn = document.createElement('button');
    generateBtn.type = 'button';
    generateBtn.className = 'btn-sm btn-info mt-2';
    generateBtn.innerHTML = '<i class="fas fa-random mr-1"></i>Generate Secure Password';
    
    generateBtn.addEventListener('click', function() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        passwordField.value = password;
        document.getElementById('password_confirmation').value = password;
    });
    
    passwordField.parentNode.appendChild(generateBtn);
});
</script>

<style>
.required {
    color: #d4af37;
}

.input-with-icon {
    position: relative;
}

.input-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}

.input-icon:hover {
    color: #d4af37;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 0.25rem;
    border: 2px solid #d1d5db;
    background-color: white;
}

.form-checkbox:checked {
    background-color: #d4af37;
    border-color: #d4af37;
}

.info-card-item {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e5e5e5;
    transition: all 0.3s ease;
}

.info-card-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.15);
    border-color: #d4af37;
}

.info-card-item .card-title {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.info-card-item .card-value {
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
