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
        // New costing fields
        'labor_cost_percentage',
        'overhead_cost_percentage',
        'suggested_price',
        'last_cost_calculation_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_active' => 'boolean',
        'weight' => 'decimal:2',
        'specifications' => 'array',
        'labor_cost_percentage' => 'decimal:2',
        'overhead_cost_percentage' => 'decimal:2',
        'suggested_price' => 'decimal:2',
        'last_cost_calculation_date' => 'datetime',
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

    // New Methods
    public function getActiveBOM()
    {
        return $this->billOfMaterials()
            ->where('is_default', true)
            ->where('status', 'active')
            ->first();
    }

    public function calculateCost()
    {
        $calculator = app(\App\Services\ProductCostCalculator::class);
        return $calculator->updateProductCost($this);
    }

    // Accessors
    public function getSuggestedPriceAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Calculate if not set
        if ($this->cost) {
            $targetMargin = 40; // Default margin
            return $this->cost / (1 - ($targetMargin / 100));
        }

        return null;
    }

    public function getProfitMarginAttribute()
    {
        if (!$this->cost || !$this->price) {
            return null;
        }

        return (($this->price - $this->cost) / $this->price) * 100;
    }
}
