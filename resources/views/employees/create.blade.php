@extends('layouts.app')

@section('title', 'Add New Employee')
@section('page-title', 'Add New Employee')

@section('content')
<div x-data="employeeForm()">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">👤 Add New Employee</h2>
                    <p class="text-gray-600 mt-1">Create a new employee record</p>
                </div>
                <a href="{{ route('employees.index') }}" 
                   class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Back to Employees
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('employees.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            User Account <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id" id="user_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('user_id') border-red-500 @enderror">
                            <option value="">Select User Account</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Employee ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="employee_id" id="employee_id" required
                               value="{{ old('employee_id') }}"
                               placeholder="e.g., EMP001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('employee_id') border-red-500 @enderror">
                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" name="phone" id="phone"
                               value="{{ old('phone') }}"
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
                               value="{{ old('birth_date') }}"
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
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
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
                               value="{{ old('position') }}"
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
                            <option value="Production" {{ old('department') == 'Production' ? 'selected' : '' }}>Production</option>
                            <option value="Quality Control" {{ old('department') == 'Quality Control' ? 'selected' : '' }}>Quality Control</option>
                            <option value="Maintenance" {{ old('department') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Administration" {{ old('department') == 'Administration' ? 'selected' : '' }}>Administration</option>
                            <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                            <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
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
                               value="{{ old('salary') }}"
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
                               value="{{ old('hire_date') }}"
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
                            <option value="active" {{ old('employment_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('employment_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="terminated" {{ old('employment_status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
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
                           value="{{ old('skills') }}"
                           placeholder="e.g., Leadership, Problem Solving, Technical Skills"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('skills') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Enter skills separated by commas</p>
                    @error('skills')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                        Create Employee
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
