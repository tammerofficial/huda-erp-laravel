@extends('layouts.app')

@section('title', 'Add New Employee')
@section('page-title', 'Add New Employee')

@section('content')
<div x-data="employeeForm()">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">👤 Add New Employee</h2>
                    <p class="text-gray-600 mt-1">Create a new employee record with complete information</p>
                </div>
                <a href="{{ route('employees.index') }}" 
                   class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Back to Employees
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                               value="{{ old('employee_id', 'EMP' . str_pad(Employee::count() + 1, 3, '0', STR_PAD_LEFT)) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('employee_id') border-red-500 @enderror">
                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('nationality') border-red-500 @enderror">
                        @error('nationality')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="civil_id" class="block text-sm font-medium text-gray-700 mb-2">Civil ID</label>
                        <input type="text" name="civil_id" id="civil_id" value="{{ old('civil_id') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('civil_id') border-red-500 @enderror">
                        @error('civil_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passport_number" class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                        <input type="text" name="passport_number" id="passport_number" value="{{ old('passport_number') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('passport_number') border-red-500 @enderror">
                        @error('passport_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passport_expiry" class="block text-sm font-medium text-gray-700 mb-2">Passport Expiry</label>
                        <input type="date" name="passport_expiry" id="passport_expiry" value="{{ old('passport_expiry') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('passport_expiry') border-red-500 @enderror">
                        @error('passport_expiry')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-2">Blood Type</label>
                        <select name="blood_type" id="blood_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('blood_type') border-red-500 @enderror">
                            <option value="">Select Blood Type</option>
                            <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                        @error('blood_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-phone mr-2 text-red-600"></i>
                    Emergency Contact
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact Name</label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('emergency_contact_name') border-red-500 @enderror">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('emergency_contact_phone') border-red-500 @enderror">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_relation" class="block text-sm font-medium text-gray-700 mb-2">Relation</label>
                        <select name="emergency_contact_relation" id="emergency_contact_relation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('emergency_contact_relation') border-red-500 @enderror">
                            <option value="">Select Relation</option>
                            <option value="Spouse" {{ old('emergency_contact_relation') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                            <option value="Parent" {{ old('emergency_contact_relation') == 'Parent' ? 'selected' : '' }}>Parent</option>
                            <option value="Sibling" {{ old('emergency_contact_relation') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                            <option value="Child" {{ old('emergency_contact_relation') == 'Child' ? 'selected' : '' }}>Child</option>
                            <option value="Friend" {{ old('emergency_contact_relation') == 'Friend' ? 'selected' : '' }}>Friend</option>
                            <option value="Other" {{ old('emergency_contact_relation') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('emergency_contact_relation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
                        <input type="text" name="position" id="position" required value="{{ old('position') }}"
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
                            <option value="Warehouse" {{ old('department') == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                            <option value="Maintenance" {{ old('department') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Administration" {{ old('department') == 'Administration' ? 'selected' : '' }}>Administration</option>
                            <option value="HR" {{ old('department') == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        </select>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">Salary (KWD)</label>
                        <input type="number" name="salary" id="salary" step="0.01" min="0" value="{{ old('salary') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('salary') border-red-500 @enderror">
                        @error('salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Hire Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="hire_date" id="hire_date" required value="{{ old('hire_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('hire_date') border-red-500 @enderror">
                        @error('hire_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="probation_end_date" class="block text-sm font-medium text-gray-700 mb-2">Probation End Date</label>
                        <input type="date" name="probation_end_date" id="probation_end_date" value="{{ old('probation_end_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('probation_end_date') border-red-500 @enderror">
                        @error('probation_end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="work_schedule" class="block text-sm font-medium text-gray-700 mb-2">Work Schedule</label>
                        <select name="work_schedule" id="work_schedule"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('work_schedule') border-red-500 @enderror">
                            <option value="">Select Work Schedule</option>
                            <option value="Full Time" {{ old('work_schedule') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                            <option value="Part Time" {{ old('work_schedule') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                            <option value="Shift Work" {{ old('work_schedule') == 'Shift Work' ? 'selected' : '' }}>Shift Work</option>
                            <option value="Flexible" {{ old('work_schedule') == 'Flexible' ? 'selected' : '' }}>Flexible</option>
                        </select>
                        @error('work_schedule')
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

            <!-- Leave Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
                    Leave Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="vacation_days_entitled" class="block text-sm font-medium text-gray-700 mb-2">Vacation Days Entitled</label>
                        <input type="number" name="vacation_days_entitled" id="vacation_days_entitled" min="0" value="{{ old('vacation_days_entitled', 21) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('vacation_days_entitled') border-red-500 @enderror">
                        @error('vacation_days_entitled')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vacation_days_used" class="block text-sm font-medium text-gray-700 mb-2">Vacation Days Used</label>
                        <input type="number" name="vacation_days_used" id="vacation_days_used" min="0" value="{{ old('vacation_days_used', 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('vacation_days_used') border-red-500 @enderror">
                        @error('vacation_days_used')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sick_days_used" class="block text-sm font-medium text-gray-700 mb-2">Sick Days Used</label>
                        <input type="number" name="sick_days_used" id="sick_days_used" min="0" value="{{ old('sick_days_used', 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('sick_days_used') border-red-500 @enderror">
                        @error('sick_days_used')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Document Uploads -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-file-upload mr-2 text-orange-600"></i>
                    Document Uploads
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('profile_photo') border-red-500 @enderror">
                        @error('profile_photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_card_front" class="block text-sm font-medium text-gray-700 mb-2">ID Card Front</label>
                        <input type="file" name="id_card_front" id="id_card_front" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('id_card_front') border-red-500 @enderror">
                        @error('id_card_front')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_card_back" class="block text-sm font-medium text-gray-700 mb-2">ID Card Back</label>
                        <input type="file" name="id_card_back" id="id_card_back" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('id_card_back') border-red-500 @enderror">
                        @error('id_card_back')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passport_photo" class="block text-sm font-medium text-gray-700 mb-2">Passport Photo</label>
                        <input type="file" name="passport_photo" id="passport_photo" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('passport_photo') border-red-500 @enderror">
                        @error('passport_photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="visa_photo" class="block text-sm font-medium text-gray-700 mb-2">Visa Photo</label>
                        <input type="file" name="visa_photo" id="visa_photo" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('visa_photo') border-red-500 @enderror">
                        @error('visa_photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contract_document" class="block text-sm font-medium text-gray-700 mb-2">Contract Document</label>
                        <input type="file" name="contract_document" id="contract_document" accept=".pdf,.doc,.docx"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('contract_document') border-red-500 @enderror">
                        @error('contract_document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="medical_certificate" class="block text-sm font-medium text-gray-700 mb-2">Medical Certificate</label>
                        <input type="file" name="medical_certificate" id="medical_certificate" accept=".pdf,.doc,.docx,image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('medical_certificate') border-red-500 @enderror">
                        @error('medical_certificate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="other_documents" class="block text-sm font-medium text-gray-700 mb-2">Other Documents</label>
                        <input type="file" name="other_documents" id="other_documents" accept=".pdf,.doc,.docx,image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('other_documents') border-red-500 @enderror">
                        @error('other_documents')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Skills & Notes -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-cogs mr-2 text-indigo-600"></i>
                    Skills & Qualifications
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">Skills (comma-separated)</label>
                        <textarea name="skills" id="skills" rows="3" placeholder="Enter skills separated by commas"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('skills') border-red-500 @enderror">{{ old('skills') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Enter skills separated by commas</p>
                        @error('skills')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Additional notes about the employee"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('employees.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    Create Employee
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function employeeForm() {
    return {
        // Form logic can be added here
    }
}
</script>
@endsection