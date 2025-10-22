<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'production_stage_id',
        'date',
        'hours_worked',
        'hourly_rate',
        'total_amount',
        'work_type',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'hours_worked' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function productionStage()
    {
        return $this->belongsTo(ProductionStage::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('approved_at');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeForPeriod($query, $start, $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }

    public function scopeByWorkType($query, $type)
    {
        return $query->where('work_type', $type);
    }

    // Accessors
    public function getIsApprovedAttribute()
    {
        return !is_null($this->approved_at);
    }
}
