@extends('layouts.app')

@section('title', 'Role Management')
@section('page-title', 'Role Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="card mb-6" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
        <div class="card-body text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2" style="color: #d4af37;">
                        <i class="fas fa-users-cog mr-3"></i>Role Management
                    </h1>
                    <p class="text-white opacity-90">Manage system users, roles, and access control</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('roles.create') }}" class="btn-primary">
                        <i class="fas fa-user-plus mr-2"></i>Add New User
                    </a>
                    <button onclick="toggleFilters()" class="btn-secondary">
                        <i class="fas fa-filter mr-2"></i>Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Smart Filters Section -->
    <div id="filters-section" class="card mb-6" style="display: none;">
        <div class="card-header">
            <h3 class="text-lg font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-filter mr-2" style="color: #d4af37;"></i>
                Smart Filters
            </h3>
        </div>
        <div class="card-body">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="form-group-enhanced">
                    <label>Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Name, email, or phone..." class="form-control">
                </div>

                <!-- Role Filter -->
                <div class="form-group-enhanced">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="form-group-enhanced">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Last Login Filter -->
                <div class="form-group-enhanced">
                    <label>Last Login</label>
                    <select name="last_login" class="form-control">
                        <option value="">Any Time</option>
                        <option value="1" {{ request('last_login') == '1' ? 'selected' : '' }}>Last 24 Hours</option>
                        <option value="7" {{ request('last_login') == '7' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ request('last_login') == '30' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ request('last_login') == '90' ? 'selected' : '' }}>Last 90 Days</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="form-group-enhanced">
                    <label>Created From</label>
                    <input type="date" name="created_from" value="{{ request('created_from') }}" class="form-control">
                </div>

                <div class="form-group-enhanced">
                    <label>Created To</label>
                    <input type="date" name="created_to" value="{{ request('created_to') }}" class="form-control">
                </div>

                <!-- Filter Actions -->
                <div class="form-group-enhanced flex items-end">
                    <div class="flex space-x-2 w-full">
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn-secondary flex-1">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card mb-6">
        <div class="card-body">
            <form id="bulk-form" method="POST" action="{{ route('roles.bulk-action') }}">
                @csrf
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <input type="checkbox" id="select-all" class="form-checkbox">
                        <label for="select-all" class="text-sm font-medium">Select All</label>
                        
                        <select name="action" class="form-control" style="width: auto;" required>
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="assign_role">Assign Role</option>
                            <option value="delete">Delete Selected</option>
                        </select>

                        <select name="role" class="form-control" style="width: auto; display: none;" id="role-select">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn-warning" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-play mr-2"></i>Execute
                        </button>
                    </div>

                    <div class="text-sm text-gray-600">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="pb-3">
                                <input type="checkbox" id="select-all-table" class="form-checkbox">
                            </th>
                            <th class="pb-3">User</th>
                            <th class="pb-3">Email</th>
                            <th class="pb-3">Role</th>
                            <th class="pb-3">Phone</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Last Login</th>
                            <th class="pb-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="py-3">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-checkbox user-checkbox">
                            </td>
                            <td class="py-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mr-3"
                                         style="background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold" style="color: #1a1a1a;">{{ $user->name }}</div>
                                        <div class="text-sm" style="color: #666;">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="text-sm">{{ $user->email }}</div>
                            </td>
                            <td class="py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="text-sm">{{ $user->phone ?? 'Not Set' }}</span>
                            </td>
                            <td class="py-3">
                                @if($user->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="text-sm" style="color: #666;">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('roles.show', $user) }}" class="btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('roles.edit', $user) }}" class="btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if(!$user->hasRole('super_admin'))
                                        <form method="POST" action="{{ route('roles.toggle-status', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}" 
                                                    title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('roles.reset-password', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-secondary" title="Reset Password" 
                                                    onclick="return confirm('Reset password to default?')">
                                                <i class="fas fa-key"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('roles.destroy', $user) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-sm btn-danger" title="Delete" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <i class="fas fa-users text-6xl mb-4" style="color: #666; opacity: 0.3;"></i>
                                <p style="color: #666;">No users found matching your criteria.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleFilters() {
    const filtersSection = document.getElementById('filters-section');
    filtersSection.style.display = filtersSection.style.display === 'none' ? 'block' : 'none';
}

// Select All Functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

document.getElementById('select-all-table').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Show/Hide Role Select for Bulk Actions
document.querySelector('select[name="action"]').addEventListener('change', function() {
    const roleSelect = document.getElementById('role-select');
    if (this.value === 'assign_role') {
        roleSelect.style.display = 'block';
        roleSelect.required = true;
    } else {
        roleSelect.style.display = 'none';
        roleSelect.required = false;
    }
});

// Update select all when individual checkboxes change
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.user-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        
        document.getElementById('select-all').checked = allCheckboxes.length === checkedCheckboxes.length;
        document.getElementById('select-all-table').checked = allCheckboxes.length === checkedCheckboxes.length;
    });
});
</script>

<style>
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-sm i {
    font-size: 0.75rem;
}

.form-checkbox {
    width: 1rem;
    height: 1rem;
    border-radius: 0.25rem;
    border: 2px solid #d1d5db;
    background-color: white;
}

.form-checkbox:checked {
    background-color: #d4af37;
    border-color: #d4af37;
}
</style>
@endsection
