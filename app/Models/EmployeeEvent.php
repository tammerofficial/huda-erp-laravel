<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'event_type',
        'status',
        'is_recurring',
        'recurring_type',
        'color',
        'is_all_day',
        'reminder_settings',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_recurring' => 'boolean',
        'is_all_day' => 'boolean',
        'reminder_settings' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('event_date', '>=', now())
                    ->where('event_date', '<=', now()->addDays($days))
                    ->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeBirthdays($query)
    {
        return $query->where('event_type', 'birthday');
    }

    public function scopeVacations($query)
    {
        return $query->where('event_type', 'vacation');
    }

    public function scopeAnniversaries($query)
    {
        return $query->where('event_type', 'anniversary');
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('M d, Y');
    }

    public function getFormattedTimeAttribute()
    {
        if ($this->is_all_day) {
            return 'All Day';
        }
        
        if ($this->start_time && $this->end_time) {
            return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
        }
        
        if ($this->start_time) {
            return $this->start_time->format('H:i');
        }
        
        return null;
    }

    public function getDaysUntilAttribute()
    {
        $days = now()->diffInDays($this->event_date, false);
        
        if ($days < 0) {
            return 'Past';
        } elseif ($days == 0) {
            return 'Today';
        } elseif ($days == 1) {
            return 'Tomorrow';
        } else {
            return "In {$days} days";
        }
    }
}