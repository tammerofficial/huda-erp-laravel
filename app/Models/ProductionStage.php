<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_order_id',
        'stage_name',
        'status',
        'employee_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'quality_checks',
        'sequence_order',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'quality_checks' => 'array',
    ];

    // العلاقات
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in-progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInMinutes($this->end_time);
        }
        return null;
    }
}
