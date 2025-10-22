@extends('layouts.app')

@section('title', 'Employee Details')
@section('page-title', 'Employee Details')

@section('content')
<div x-data="employeeShow()">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold mr-4">
                        {{ substr($employee->user->name ?? 'N/A', 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $employee->user->name ?? 'N/A' }}</h2>
                        <p class="text-gray-600">{{ $employee->position ?? 'N/A' }} - {{ $employee->department ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Employee ID: {{ $employee->employee_id ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('employees.edit', $employee) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Employee
                    </a>
                    <a href="{{ route('employees.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Back to Employees
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                            <p class="text-gray-900">{{ $employee->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-900">{{ $employee->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                            <p class="text-gray-900">{{ $employee->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Birth Date</label>
                            <p class="text-gray-900">{{ $employee->birth_date ? $employee->birth_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                            <p class="text-gray-900">{{ $employee->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-briefcase mr-2 text-green-600"></i>
                        Employment Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Position</label>
                            <p class="text-gray-900">{{ $employee->position ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                            <p class="text-gray-900">{{ $employee->department ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Salary</label>
                            <p class="text-gray-900">{{ $employee->salary ? 'KWD ' . number_format($employee->salary, 2) : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Hire Date</label>
                            <p class="text-gray-900">{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Employment Status</label>
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-yellow-100 text-yellow-800',
                                    'terminated' => 'bg-red-100 text-red-800'
                                ];
                                $status = $employee->employment_status ?? 'inactive';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Years of Service</label>
                            <p class="text-gray-900">
                                @if($employee->hire_date)
                                    {{ $employee->hire_date->diffInYears(now()) }} years
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Skills & Qualifications -->
                @if($employee->skills)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cogs mr-2 text-purple-600"></i>
                        Skills & Qualifications
                    </h3>
                    
                    <div class="flex flex-wrap gap-2">
                        @if(is_array($employee->skills))
                            @foreach($employee->skills as $skill)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        @else
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">
                                {{ $employee->skills }}
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- QR Code Section -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-qrcode mr-2 text-orange-600"></i>
                        QR Code
                    </h3>
                    
                    @if($employee->qr_code)
                        <div class="text-center">
                            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                                <div class="w-32 h-32 mx-auto bg-white rounded border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <i class="fas fa-qrcode text-4xl text-gray-400"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">QR Code: {{ $employee->qr_code }}</p>
                            <form action="{{ route('employees.generate-qr', $employee) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors w-full justify-center">
                                    <i class="fas fa-sync mr-2"></i>
                                    Regenerate QR
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                                <div class="w-32 h-32 mx-auto bg-white rounded border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <i class="fas fa-qrcode text-4xl text-gray-400"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">No QR code generated yet</p>
                            <form action="{{ route('employees.generate-qr', $employee) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors w-full justify-center">
                                    <i class="fas fa-qrcode mr-2"></i>
                                    Generate QR Code
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-bolt mr-2 text-yellow-600"></i>
                        Quick Actions
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('employees.edit', $employee) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Employee
                        </a>
                        
                        <button @click="deleteEmployee({{ $employee->id }})"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Employee
                        </button>
                        
                        <form id="delete-form-{{ $employee->id }}" 
                              action="{{ route('employees.destroy', $employee) }}" 
                              method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
                        Statistics
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Years of Service</span>
                            <span class="font-semibold text-gray-900">
                                @if($employee->hire_date)
                                    {{ $employee->hire_date->diffInYears(now()) }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            @php
                                $statusColors = [
                                    'active' => 'text-green-600',
                                    'inactive' => 'text-yellow-600',
                                    'terminated' => 'text-red-600'
                                ];
                                $status = $employee->employment_status ?? 'inactive';
                            @endphp
                            <span class="font-semibold {{ $statusColors[$status] ?? 'text-gray-600' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Department</span>
                            <span class="font-semibold text-gray-900">{{ $employee->department ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function employeeShow() {
    return {
        deleteEmployee(employeeId) {
            if (confirm('Are you sure you want to delete this employee? This action cannot be undone.')) {
                document.getElementById(`delete-form-${employeeId}`).submit();
            }
        }
    }
}
</script>
@endsection
