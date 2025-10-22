<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::where('employment_status', 'active')->get();
        
        if ($employees->isEmpty()) {
            $this->command->warn('No active employees found. Please run EmployeeSeeder first.');
            return;
        }

        // Generate attendance records for the last 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // Skip weekends (Friday and Saturday)
            if ($date->dayOfWeek == 5 || $date->dayOfWeek == 6) {
                continue;
            }
            
            foreach ($employees as $employee) {
                // 90% attendance rate
                if (rand(1, 100) <= 90) {
                    $checkIn = $date->copy()->setTime(8, rand(0, 30)); // 8:00-8:30 AM
                    $checkOut = $date->copy()->setTime(16, rand(0, 30)); // 4:00-4:30 PM
                    
                    $status = 'present';
                    if ($checkIn->hour > 8 || ($checkIn->hour == 8 && $checkIn->minute > 15)) {
                        $status = 'late';
                    }
                    
                    AttendanceRecord::create([
                        'employee_id' => $employee->id,
                        'date' => $date->format('Y-m-d'),
                        'check_in' => $checkIn->format('H:i'),
                        'check_out' => $checkOut->format('H:i'),
                        'status' => $status,
                        'notes' => $status === 'late' ? 'Arrived late due to traffic' : null,
                    ]);
                } else {
                    // 10% absence rate
                    AttendanceRecord::create([
                        'employee_id' => $employee->id,
                        'date' => $date->format('Y-m-d'),
                        'status' => 'absent',
                        'notes' => 'Sick leave',
                    ]);
                }
            }
        }

        $this->command->info('Attendance records seeded successfully!');
    }
}
