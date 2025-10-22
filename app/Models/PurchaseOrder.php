<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'order_number',
        'order_date',
        'delivery_date',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'status',
        'payment_status',
        'payment_terms',
        'shipping_address',
        'priority',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    // العلاقات
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Alias for easier access
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
}
