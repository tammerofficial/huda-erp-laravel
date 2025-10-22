<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'material_id',
        'quantity',
        'unit',
        'unit_cost',
        'total_cost',
        'notes',
        'sequence_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    // العلاقات
    public function billOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Accessor
    public function getTotalCostAttribute()
    {
        return $this->quantity * $this->unit_cost;
    }
}
