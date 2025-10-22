<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeEvent;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeEventsSeeder extends Seeder
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

        // Generate events for the next 12 months
        for ($i = 0; $i < 365; $i++) {
            $date = Carbon::now()->addDays($i);
            
            // Skip weekends for most events
            if ($date->dayOfWeek == 5 || $date->dayOfWeek == 6) {
                continue;
            }
            
            // Generate random events
            if (rand(1, 100) <= 5) { // 5% chance per day
                $employee = $employees->random();
                $eventType = $this->getRandomEventType();
                
                EmployeeEvent::create([
                    'employee_id' => $employee->id,
                    'title' => $this->getEventTitle($eventType, $employee),
                    'description' => $this->getEventDescription($eventType),
                    'event_date' => $date,
                    'start_time' => $this->getEventTime($eventType),
                    'end_time' => $this->getEventEndTime($eventType),
                    'event_type' => $eventType,
                    'status' => 'active',
                    'is_recurring' => $this->isRecurringEvent($eventType),
                    'recurring_type' => $this->getRecurringType($eventType),
                    'color' => $this->getEventColor($eventType),
                    'is_all_day' => $this->isAllDayEvent($eventType),
                    'reminder_settings' => $this->getReminderSettings($eventType),
                    'created_by' => 1, // Admin user
                ]);
            }
        }

        // Generate birthday events for all employees
        foreach ($employees as $employee) {
            if ($employee->birth_date) {
                $birthday = $employee->birth_date->copy()->year(now()->year);
                if ($birthday->isPast()) {
                    $birthday->addYear();
                }
                
                EmployeeEvent::create([
                    'employee_id' => $employee->id,
                    'title' => "🎂 {$employee->user->name}'s Birthday",
                    'description' => "Birthday celebration for {$employee->user->name}",
                    'event_date' => $birthday,
                    'event_type' => 'birthday',
                    'status' => 'active',
                    'is_recurring' => true,
                    'recurring_type' => 'yearly',
                    'color' => '#ff6b6b',
                    'is_all_day' => true,
                    'reminder_settings' => ['email' => true, 'days_before' => 7],
                    'created_by' => 1,
                ]);
            }
            
            if ($employee->hire_date) {
                $anniversary = $employee->hire_date->copy()->year(now()->year);
                if ($anniversary->isPast()) {
                    $anniversary->addYear();
                }
                
                EmployeeEvent::create([
                    'employee_id' => $employee->id,
                    'title' => "🎉 {$employee->user->name}'s Work Anniversary",
                    'description' => "Work anniversary celebration for {$employee->user->name}",
                    'event_date' => $anniversary,
                    'event_type' => 'anniversary',
                    'status' => 'active',
                    'is_recurring' => true,
                    'recurring_type' => 'yearly',
                    'color' => '#4ecdc4',
                    'is_all_day' => true,
                    'reminder_settings' => ['email' => true, 'days_before' => 14],
                    'created_by' => 1,
                ]);
            }
        }

        $this->command->info('Employee events seeded successfully!');
    }
    
    private function getRandomEventType()
    {
        $types = ['vacation', 'sick_leave', 'holiday', 'meeting', 'training', 'performance_review', 'contract_renewal', 'probation_end'];
        return $types[array_rand($types)];
    }
    
    private function getEventTitle($eventType, $employee)
    {
        $titles = [
            'vacation' => "🏖️ {$employee->user->name} - Vacation",
            'sick_leave' => "🤒 {$employee->user->name} - Sick Leave",
            'holiday' => "🎉 {$employee->user->name} - Holiday",
            'meeting' => "📅 {$employee->user->name} - Team Meeting",
            'training' => "📚 {$employee->user->name} - Training Session",
            'performance_review' => "📊 {$employee->user->name} - Performance Review",
            'contract_renewal' => "📝 {$employee->user->name} - Contract Renewal",
            'probation_end' => "✅ {$employee->user->name} - Probation End"
        ];
        
        return $titles[$eventType] ?? "Event for {$employee->user->name}";
    }
    
    private function getEventDescription($eventType)
    {
        $descriptions = [
            'vacation' => 'Employee vacation time',
            'sick_leave' => 'Employee sick leave',
            'holiday' => 'Public holiday or company holiday',
            'meeting' => 'Team meeting or department meeting',
            'training' => 'Employee training session',
            'performance_review' => 'Annual performance review meeting',
            'contract_renewal' => 'Contract renewal discussion',
            'probation_end' => 'End of probationary period'
        ];
        
        return $descriptions[$eventType] ?? 'Employee event';
    }
    
    private function getEventTime($eventType)
    {
        if (in_array($eventType, ['vacation', 'sick_leave', 'holiday'])) {
            return null; // All day events
        }
        
        return Carbon::createFromTime(9, rand(0, 59), 0)->format('H:i');
    }
    
    private function getEventEndTime($eventType)
    {
        if (in_array($eventType, ['vacation', 'sick_leave', 'holiday'])) {
            return null; // All day events
        }
        
        return Carbon::createFromTime(17, rand(0, 59), 0)->format('H:i');
    }
    
    private function isRecurringEvent($eventType)
    {
        return in_array($eventType, ['birthday', 'anniversary', 'holiday']);
    }
    
    private function getRecurringType($eventType)
    {
        if ($eventType === 'holiday') {
            return 'yearly';
        }
        
        return null;
    }
    
    private function getEventColor($eventType)
    {
        $colors = [
            'vacation' => '#4ecdc4',
            'sick_leave' => '#ff6b6b',
            'holiday' => '#45b7d1',
            'meeting' => '#96ceb4',
            'training' => '#feca57',
            'performance_review' => '#ff9ff3',
            'contract_renewal' => '#54a0ff',
            'probation_end' => '#5f27cd'
        ];
        
        return $colors[$eventType] ?? '#6c757d';
    }
    
    private function isAllDayEvent($eventType)
    {
        return in_array($eventType, ['vacation', 'sick_leave', 'holiday', 'birthday', 'anniversary']);
    }
    
    private function getReminderSettings($eventType)
    {
        $settings = [
            'email' => true,
            'days_before' => rand(1, 7)
        ];
        
        if (in_array($eventType, ['performance_review', 'contract_renewal'])) {
            $settings['days_before'] = 14;
        }
        
        return $settings;
    }
}
