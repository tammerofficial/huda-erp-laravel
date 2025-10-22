@extends('layouts.app')

@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')

@section('content')
<div x-data="employeeForm()">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">✏️ Edit Employee</h2>
                    <p class="text-gray-600 mt-1">Update employee information</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('employees.show', $employee) }}" 
                       class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        View Employee
                    </a>
                    <a href="{{ route('employees.index') }}" 
                       class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Back to Employees
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('employees.update', $employee) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            User Account
                        </label>
                        <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600">
                            {{ $employee->user->name ?? 'N/A' }} ({{ $employee->user->email ?? 'N/A' }})
                        </div>
                        <p class="mt-1 text-sm text-gray-500">User account cannot be changed after creation</p>
                    </div>

                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Employee ID
                        </label>
                        <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600">
                            {{ $employee->employee_id ?? 'N/A' }}
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Employee ID cannot be changed after creation</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" name="phone" id="phone"
                               value="{{ old('phone', $employee->phone) }}"
                               placeholder="+965 1234 5678"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Birth Date
                        </label>
                        <input type="date" name="birth_date" id="birth_date"
                               value="{{ old('birth_date', $employee->birth_date?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address
                    </label>
                    <textarea name="address" id="address" rows="3"
                              placeholder="Enter full address..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $employee->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Employment Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-briefcase mr-2 text-green-600"></i>
                    Employment Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                            Position <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="position" id="position" required
                               value="{{ old('position', $employee->position) }}"
                               placeholder="e.g., Production Manager"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('position') border-red-500 @enderror">
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department" id="department" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('department') border-red-500 @enderror">
                            <option value="">Select Department</option>
                            <option value="Production" {{ old('department', $employee->department) == 'Production' ? 'selected' : '' }}>Production</option>
                            <option value="Quality Control" {{ old('department', $employee->department) == 'Quality Control' ? 'selected' : '' }}>Quality Control</option>
                            <option value="Maintenance" {{ old('department', $employee->department) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Administration" {{ old('department', $employee->department) == 'Administration' ? 'selected' : '' }}>Administration</option>
                            <option value="Sales" {{ old('department', $employee->department) == 'Sales' ? 'selected' : '' }}>Sales</option>
                            <option value="Finance" {{ old('department', $employee->department) == 'Finance' ? 'selected' : '' }}>Finance</option>
                        </select>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">
                            Salary (KWD)
                        </label>
                        <input type="number" name="salary" id="salary" step="0.01" min="0"
                               value="{{ old('salary', $employee->salary) }}"
                               placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('salary') border-red-500 @enderror">
                        @error('salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Hire Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="hire_date" id="hire_date" required
                               value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('hire_date') border-red-500 @enderror">
                        @error('hire_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employment_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Employment Status <span class="text-red-500">*</span>
                        </label>
                        <select name="employment_status" id="employment_status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('employment_status') border-red-500 @enderror">
                            <option value="">Select Status</option>
                            <option value="active" {{ old('employment_status', $employee->employment_status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('employment_status', $employee->employment_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="terminated" {{ old('employment_status', $employee->employment_status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('employment_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Skills Section -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-cogs mr-2 text-purple-600"></i>
                    Skills & Qualifications
                </h3>
                
                <div>
                    <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">
                        Skills (comma-separated)
                    </label>
                    <input type="text" name="skills" id="skills"
                           value="{{ old('skills', is_array($employee->skills) ? implode(', ', $employee->skills) : $employee->skills) }}"
                           placeholder="e.g., Leadership, Problem Solving, Technical Skills"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('skills') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Enter skills separated by commas</p>
                    @error('skills')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-qrcode mr-2 text-orange-600"></i>
                    QR Code Management
                </h3>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Generate a unique QR code for this employee</p>
                        @if($employee->qr_code)
                            <p class="text-sm text-green-600 mt-1">QR Code: {{ $employee->qr_code }}</p>
                        @else
                            <p class="text-sm text-gray-500 mt-1">No QR code generated yet</p>
                        @endif
                    </div>
                    <form action="{{ route('employees.generate-qr', $employee) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                            <i class="fas fa-qrcode mr-2"></i>
                            {{ $employee->qr_code ? 'Regenerate QR' : 'Generate QR' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex justify-end space-x-4 space-x-reverse">
                    <a href="{{ route('employees.index') }}" 
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 btn-primary transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Employee
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function employeeForm() {
    return {
        // Form validation and interaction logic can be added here
        init() {
            // Initialize any form-specific functionality
        }
    }
}
</script>
@endsection
