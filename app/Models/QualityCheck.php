<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityCheck extends Model
{
    protected $fillable = [
        'production_order_id', 'product_id', 'inspector_id',
        'status', 'items_checked', 'items_passed', 'items_failed',
        'defects', 'notes', 'inspection_date'
    ];

    protected $casts = [
        'defects' => 'array',
        'inspection_date' => 'datetime',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inspector()
    {
        return $this->belongsTo(Employee::class, 'inspector_id');
    }
}
