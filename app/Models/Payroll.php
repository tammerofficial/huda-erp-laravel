<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'base_salary',
        'overtime_hours',
        'overtime_amount',
        'bonuses',
        'deductions',
        'total_amount',
        'status',
        'payment_date',
        'payment_method',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'payment_date' => 'date',
        'base_salary' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'deductions' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForPeriod($query, $start, $end)
    {
        return $query->where('period_start', '>=', $start)
                     ->where('period_end', '<=', $end);
    }

    // Accessors
    public function getNetPayAttribute()
    {
        return $this->base_salary + $this->overtime_amount + $this->bonuses - $this->deductions;
    }
}
