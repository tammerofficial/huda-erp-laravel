@extends('layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="card mb-6" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
        <div class="card-body text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2" style="color: #d4af37;">
                        <i class="fas fa-user mr-3"></i>{{ $user->name }}
                    </h1>
                    <div class="flex items-center space-x-4">
                        <span class="badge badge-info">ID: #{{ $user->id }}</span>
                        @if($user->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                        <span class="text-white opacity-90">{{ $user->email }}</span>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('roles.edit', $user) }}" class="btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit User
                    </a>
                    <a href="{{ route('roles.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - User Info & Stats -->
        <div class="lg:col-span-1">
            <!-- User Avatar & Basic Info -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-user-circle mr-2" style="color: #d4af37;"></i>
                        User Profile
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4"
                             style="background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold" style="color: #1a1a1a;">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>

                    <div class="space-y-4">
                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="card-title">Phone</div>
                            <div class="card-value">{{ $user->phone ?? 'Not Set' }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="card-title">Address</div>
                            <div class="card-value">{{ $user->address ?? 'Not Set' }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="card-title">Member Since</div>
                            <div class="card-value">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>

                        <div class="info-card-item">
                            <div class="card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-title">Last Login</div>
                            <div class="card-value" style="color: #666;">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-bolt mr-2" style="color: #d4af37;"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('roles.edit', $user) }}" class="btn-sm btn-warning w-full block text-center">
                            <i class="fas fa-edit mr-2"></i>Edit User
                        </a>

                        @if(!$user->hasRole('super_admin'))
                        <form method="POST" action="{{ route('roles.toggle-status', $user) }}" class="inline w-full">
                            @csrf
                            <button type="submit" class="btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }} w-full">
                                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} mr-2"></i>
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('roles.reset-password', $user) }}" class="inline w-full">
                            @csrf
                            <button type="submit" class="btn-sm btn-info w-full" 
                                    onclick="return confirm('Reset password to default?')">
                                <i class="fas fa-key mr-2"></i>Reset Password
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('roles.index') }}" class="btn-sm btn-secondary w-full block text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Roles & Permissions -->
        <div class="lg:col-span-2">
            
            <!-- Roles Section -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-user-tag mr-2" style="color: #d4af37;"></i>
                        Assigned Roles
                    </h3>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->roles as $role)
                            <div class="info-card-item">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-lg" style="color: #1a1a1a;">
                                            {{ ucfirst($role->name) }}
                                        </div>
                                        <div class="text-sm" style="color: #666;">
                                            {{ $role->permissions->count() }} permissions
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-user-slash text-4xl mb-4" style="color: #666; opacity: 0.3;"></i>
                            <p style="color: #666;">No roles assigned to this user</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Permissions Section -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-key mr-2" style="color: #d4af37;"></i>
                        Direct Permissions
                    </h3>
                </div>
                <div class="card-body">
                    @if($user->permissions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($user->permissions as $permission)
                            <div class="info-card-item">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-sm font-semibold" style="color: #1a1a1a;">
                                        {{ ucfirst(str_replace(['.', '_'], ' ', $permission->name)) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-key text-4xl mb-4" style="color: #666; opacity: 0.3;"></i>
                            <p style="color: #666;">No direct permissions assigned</p>
                            <p class="text-sm" style="color: #999;">Permissions are inherited from roles</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- All Permissions (Including from Roles) -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-shield-alt mr-2" style="color: #d4af37;"></i>
                        All Permissions (Including from Roles)
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $allPermissions = $user->getAllPermissions();
                    @endphp
                    
                    @if($allPermissions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($allPermissions as $permission)
                            <div class="info-card-item">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-sm font-semibold" style="color: #1a1a1a;">
                                        {{ ucfirst(str_replace(['.', '_'], ' ', $permission->name)) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-ban text-4xl mb-4" style="color: #666; opacity: 0.3;"></i>
                            <p style="color: #666;">No permissions available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-card-item {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e5e5e5;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.info-card-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.2);
    border-color: #d4af37;
}

.info-card-item .card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: white;
    font-size: 1.5rem;
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
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a1a;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.badge-info {
    background-color: #3b82f6;
    color: white;
}

.badge-success {
    background-color: #10b981;
    color: white;
}

.badge-danger {
    background-color: #ef4444;
    color: white;
}
</style>
@endsection
