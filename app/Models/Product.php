<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'cost',
        'category',
        'image_url',
        'woo_id',
        'product_type',
        'is_active',
        'stock_quantity',
        'reorder_level',
        'unit',
        'weight',
        'specifications',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_active' => 'boolean',
        'weight' => 'decimal:2',
        'specifications' => 'array',
    ];

    // العلاقات
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productionOrders()
    {
        return $this->hasMany(ProductionOrder::class);
    }

    public function billOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class);
    }

    // Note: category is now a string field, not a relationship

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
