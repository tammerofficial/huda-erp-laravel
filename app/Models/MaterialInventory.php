<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'warehouse_id',
        'quantity',
        'reorder_level',
        'max_level',
        'last_updated',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reorder_level' => 'integer',
        'max_level' => 'integer',
        'last_updated' => 'datetime',
    ];

    // العلاقات
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= reorder_level');
    }

    public function scopeOverstock($query)
    {
        return $query->whereRaw('quantity > max_level');
    }
}
