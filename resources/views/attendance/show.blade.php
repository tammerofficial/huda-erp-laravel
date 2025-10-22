@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Attendance Record #{{ $attendance->id }}</h1>
            <div class="flex space-x-4">
                <a href="{{ route('attendance.edit', $attendance) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Edit
                </a>
                <a href="{{ route('attendance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Attendance Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Employee</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                <a href="{{ route('employees.show', $attendance->employee) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $attendance->employee->user->name }}
                                </a>
                            </p>
                            <p class="text-sm text-gray-600">{{ $attendance->employee->employee_id }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Date</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $attendance->date->format('Y-m-d') }}</p>
                            <p class="text-sm text-gray-600">{{ $attendance->date->format('l, F j, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Check In</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $attendance->check_in ? $attendance->check_in->format('H:i') : 'Not recorded' }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Check Out</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $attendance->check_out ? $attendance->check_out->format('H:i') : 'Not recorded' }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Hours Worked</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $attendance->hours_worked }} hours</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Overtime Hours</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $attendance->overtime_hours }} hours</p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($attendance->notes)
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Notes</h2>
                    <p class="text-gray-900">{{ $attendance->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Status Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Status</h2>
                    
                    <div class="text-center">
                        @if($attendance->status === 'present')
                        <div class="text-6xl text-green-600 mb-4">✅</div>
                        <div class="text-2xl font-bold text-green-600 mb-2">Present</div>
                        <div class="text-sm text-gray-600">Employee was present</div>
                        @elseif($attendance->status === 'absent')
                        <div class="text-6xl text-red-600 mb-4">❌</div>
                        <div class="text-2xl font-bold text-red-600 mb-2">Absent</div>
                        <div class="text-sm text-gray-600">Employee was absent</div>
                        @elseif($attendance->status === 'late')
                        <div class="text-6xl text-yellow-600 mb-4">⏰</div>
                        <div class="text-2xl font-bold text-yellow-600 mb-2">Late</div>
                        <div class="text-sm text-gray-600">Employee was late</div>
                        @elseif($attendance->status === 'half_day')
                        <div class="text-6xl text-blue-600 mb-4">🕐</div>
                        <div class="text-2xl font-bold text-blue-600 mb-2">Half Day</div>
                        <div class="text-sm text-gray-600">Employee worked half day</div>
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between mb-2">
                                <span>Created:</span>
                                <span>{{ $attendance->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Updated:</span>
                                <span>{{ $attendance->updated_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Summary -->
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Time Summary</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Regular Hours:</span>
                            <span class="font-semibold text-gray-900">{{ $attendance->hours_worked }}h</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Overtime Hours:</span>
                            <span class="font-semibold text-gray-900">{{ $attendance->overtime_hours }}h</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <span class="text-gray-600 font-semibold">Total Hours:</span>
                            <span class="font-bold text-lg text-gray-900">{{ $attendance->hours_worked + $attendance->overtime_hours }}h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
