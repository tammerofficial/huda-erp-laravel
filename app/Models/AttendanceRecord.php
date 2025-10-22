<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'check_in', 'check_out',
        'hours_worked', 'overtime_hours', 'status', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Auto-calculate hours on save
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($record) {
            if ($record->check_in && $record->check_out) {
                $start = Carbon::parse($record->check_in);
                $end = Carbon::parse($record->check_out);
                $totalHours = $end->diffInMinutes($start) / 60;
                
                $record->hours_worked = min($totalHours, 8);
                $record->overtime_hours = max(0, $totalHours - 8);
            }
        });
    }
}
