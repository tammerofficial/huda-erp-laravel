@extends('layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-gray-900 to-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center space-x-2 mt-2">
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <span class="px-3 py-1 bg-gray-900 text-white text-xs font-medium rounded-full">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        @else
                            <span class="px-3 py-1 bg-gray-400 text-white text-xs font-medium rounded-full">
                                No Role Assigned
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('users.edit', $user) }}" 
                   class="btn-primary px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit User
                </a>
                <a href="{{ route('users.index') }}" 
                   class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">User Information</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Full Name</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $user->name }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Email Address</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $user->email }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Phone Number</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $user->phone ?? 'Not Set' }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Last Login</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Member Since</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                @if($user->address)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-gray-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Address</h4>
                            <p class="text-sm text-gray-900">{{ $user->address }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Activity & Stats Card -->
        <div class="space-y-6">
            <!-- Activity Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Activity</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Last Login</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Created</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Last Updated</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Created By</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->createdBy ? $user->createdBy->name : 'System' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-bolt text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('users.edit', $user) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit User
                    </a>
                    
                    @if($user->employee)
                    <a href="{{ route('employees.show', $user->employee) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-id-card mr-2"></i>
                        View Employee Profile
                    </a>
                    @endif
                    
                    <button onclick="toggleUserStatus({{ $user->id }})" 
                            class="w-full flex items-center justify-center px-4 py-2 {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-colors">
                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Information (if exists) -->
    @if($user->employee)
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-briefcase text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Employee Information</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Employee ID</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->employee->employee_id }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Position</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->employee->position }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Department</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->employee->department }}</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Salary</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ $user->employee->salary ? number_format($user->employee->salary, 2) . ' KWD' : 'Not Set' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Hire Date</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ $user->employee->hire_date ? $user->employee->hire_date->format('M d, Y') : 'Not Set' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Employment Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->employee->employment_status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($user->employee->employment_status) }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @if($user->employee->birth_date)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Birth Date</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->employee->birth_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    
                    @if($user->employee->nationality)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Nationality</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->employee->nationality }}</span>
                    </div>
                    @endif
                    
                    @if($user->employee->skills && count($user->employee->skills) > 0)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Skills</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($user->employee->skills, 0, 3) as $skill)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $skill }}</span>
                            @endforeach
                            @if(count($user->employee->skills) > 3)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">+{{ count($user->employee->skills) - 3 }} more</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function toggleUserStatus(userId) {
    if (confirm('Are you sure you want to change this user\'s status?')) {
        fetch(`/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the user status.');
        });
    }
}
</script>
@endsection