@extends('layouts.app')

@section('title', 'Employees Management')
@section('page-title', 'Employees Management')

@section('content')
<div x-data="employeeIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">👥 Employees Management</h2>
                <p class="text-gray-600 mt-1">Manage your workforce efficiently</p>
            </div>
            <a href="{{ route('employees.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                Add New Employee
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       x-model="searchTerm"
                       @input="filterEmployees()"
                       placeholder="Search by name, position, or department..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select x-model="selectedDepartment" 
                        @change="filterEmployees()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Departments</option>
                    <option value="Production">Production</option>
                    <option value="Quality Control">Quality Control</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Administration">Administration</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="selectedStatus" 
                        @change="filterEmployees()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="terminated">Terminated</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Employee
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Position
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Department
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hire Date
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($employees as $employee)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                        {{ substr($employee->user->name ?? 'N/A', 0, 1) }}
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $employee->user->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ID: {{ $employee->employee_id ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $employee->position ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $employee->department ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('employees.show', $employee) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('employees.edit', $employee) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="deleteEmployee({{ $employee->id }})"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $employee->id }}" 
                                      action="{{ route('employees.destroy', $employee) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                                <p>No employees found</p>
                                <a href="{{ route('employees.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Add your first employee
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $employees->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function employeeIndex() {
    return {
        searchTerm: '',
        selectedDepartment: '',
        selectedStatus: '',
        
        filterEmployees() {
            // This would typically make an AJAX request to filter employees
            // For now, we'll just show all employees
            console.log('Filtering employees...', {
                search: this.searchTerm,
                department: this.selectedDepartment,
                status: this.selectedStatus
            });
        },
        
        deleteEmployee(employeeId) {
            if (confirm('Are you sure you want to delete this employee?')) {
                document.getElementById(`delete-form-${employeeId}`).submit();
            }
        }
    }
}
</script>
@endsection
