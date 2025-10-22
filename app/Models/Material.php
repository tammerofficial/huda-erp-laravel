<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'unit',
        'unit_cost',
        'category',
        'supplier_id',
        'color',
        'size',
        'description',
        'image_url',
        'reorder_level',
        'max_stock',
        'is_active',
        'auto_purchase_enabled',
        'auto_purchase_quantity',
        'min_stock_level',
        'notes',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'is_active' => 'boolean',
        'auto_purchase_enabled' => 'boolean',
        'reorder_level' => 'integer',
        'max_stock' => 'integer',
        'auto_purchase_quantity' => 'integer',
        'min_stock_level' => 'integer',
    ];

    // العلاقات
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function bomItems()
    {
        return $this->hasMany(BomItem::class);
    }

    public function inventories()
    {
        return $this->hasMany(MaterialInventory::class);
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM material_inventories WHERE material_id = materials.id) <= materials.min_stock_level');
    }

    // Accessors & Helpers
    public function getAvailableQuantityAttribute()
    {
        return $this->inventories()->sum('quantity');
    }

    public function isLowStock()
    {
        return $this->available_quantity <= $this->min_stock_level;
    }

    public function needsAutoPurchase()
    {
        return $this->auto_purchase_enabled && $this->isLowStock();
    }
}
