<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'version',
        'status',
        'description',
        'total_cost',
        'is_default',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'total_cost' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bomItems()
    {
        return $this->hasMany(BomItem::class, 'bom_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLatestVersion($query)
    {
        return $query->orderBy('version', 'desc');
    }
}
