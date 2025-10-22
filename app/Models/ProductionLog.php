<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionLog extends Model
{
    protected $fillable = [
        'employee_id', 'production_stage_id', 'product_id',
        'pieces_completed', 'rate_per_piece', 'start_time',
        'end_time', 'duration_minutes', 'earnings',
        'expected_duration', 'efficiency_rate',
        'quality_status', 'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'rate_per_piece' => 'decimal:3',
        'earnings' => 'decimal:3',
        'efficiency_rate' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function productionStage()
    {
        return $this->belongsTo(ProductionStage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($log) {
            // حساب المدة
            if ($log->start_time && $log->end_time) {
                $log->duration_minutes = $log->start_time->diffInMinutes($log->end_time);
            }
            
            // حساب الأرباح
            if ($log->pieces_completed && $log->rate_per_piece) {
                $log->earnings = $log->pieces_completed * $log->rate_per_piece;
            }
            
            // حساب الكفاءة
            if ($log->expected_duration && $log->duration_minutes) {
                $log->efficiency_rate = ($log->expected_duration / $log->duration_minutes) * 100;
            }
        });
    }
}
