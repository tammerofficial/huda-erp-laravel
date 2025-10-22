<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'phone',
        'address',
        'position',
        'department',
        'salary',
        'hire_date',
        'birth_date',
        'employment_status',
        'qr_code',
        'skills',
        'notes',
        'salary_type',
        'rate_per_hour',
        'rate_per_piece',
        'attendance_device_id',
        'efficiency_rating',
        'current_workload',
        'qr_image_path',
        'qr_enabled',
        // New fields
        'nationality',
        'civil_id',
        'passport_number',
        'passport_expiry',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'probation_end_date',
        'work_schedule',
        'vacation_days_entitled',
        'vacation_days_used',
        'sick_days_used',
        'documents',
        'profile_photo',
        'id_card_front',
        'id_card_back',
        'passport_photo',
        'visa_photo',
        'contract_document',
        'medical_certificate',
        'other_documents',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'birth_date' => 'date',
        'salary' => 'decimal:2',
        'skills' => 'array',
        'rate_per_hour' => 'decimal:3',
        'rate_per_piece' => 'decimal:3',
        'efficiency_rating' => 'decimal:2',
        'qr_enabled' => 'boolean',
        'passport_expiry' => 'date',
        'probation_end_date' => 'date',
        'documents' => 'array',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productionStages()
    {
        return $this->hasMany(ProductionStage::class);
    }

    public function managedWarehouses()
    {
        return $this->hasMany(Warehouse::class, 'manager_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function productionLogs()
    {
        return $this->hasMany(ProductionLog::class);
    }

    public function qualityChecks()
    {
        return $this->hasMany(QualityCheck::class, 'inspector_id');
    }

    public function events()
    {
        return $this->hasMany(EmployeeEvent::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->name : 'N/A';
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getYearsOfServiceAttribute()
    {
        return $this->hire_date ? $this->hire_date->diffInYears(now()) : null;
    }

    public function getVacationDaysRemainingAttribute()
    {
        return $this->vacation_days_entitled - $this->vacation_days_used;
    }

    public function getIsOnProbationAttribute()
    {
        return $this->probation_end_date && $this->probation_end_date->isFuture();
    }

    public function getUpcomingBirthdayAttribute()
    {
        if (!$this->birth_date) return null;
        
        $thisYear = now()->year;
        $birthdayThisYear = $this->birth_date->copy()->year($thisYear);
        
        if ($birthdayThisYear->isPast()) {
            $birthdayThisYear->addYear();
        }
        
        return $birthdayThisYear;
    }

    public function getUpcomingAnniversaryAttribute()
    {
        if (!$this->hire_date) return null;
        
        $thisYear = now()->year;
        $anniversaryThisYear = $this->hire_date->copy()->year($thisYear);
        
        if ($anniversaryThisYear->isPast()) {
            $anniversaryThisYear->addYear();
        }
        
        return $anniversaryThisYear;
    }
}
