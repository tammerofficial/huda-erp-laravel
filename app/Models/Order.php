<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_number',
        'order_date',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'status',
        'payment_status',
        'payment_type',
        'woo_id',
        'delivery_date',
        'shipping_address',
        'priority',
        'created_by',
        'notes',
        // New costing fields
        'shipping_cost',
        'shipping_country',
        'order_weight',
        'material_cost',
        'labor_cost',
        'overhead_cost',
        'total_cost',
        'profit_margin',
        // Analytics fields
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'referrer',
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'order_weight' => 'decimal:2',
        'material_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'overhead_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'profit_margin' => 'decimal:2',
    ];

    // العلاقات
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productionOrders()
    {
        return $this->hasMany(ProductionOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
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

    public function scopeOnHold($query)
    {
        return $query->where('status', 'on-hold');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // New Scopes
    public function scopeProfitable($query)
    {
        return $query->where('profit_margin', '>', 0);
    }

    // Accessors
    public function getProfitAmountAttribute()
    {
        return $this->final_amount - ($this->total_cost ?? 0);
    }

    // Methods
    public function recalculateCosts()
    {
        $costCalculator = app(\App\Services\ProductCostCalculator::class);
        $shippingCalculator = app(\App\Services\ShippingCalculator::class);

        // Calculate product costs
        $orderItems = $this->orderItems->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        $costs = $costCalculator->calculateOrderCosts($orderItems);

        // Calculate shipping
        $shipping = $shippingCalculator->calculateOrderShipping($this);

        // Calculate profit margin
        $totalCost = $costs['total_cost'] + $shipping['shipping_cost'];
        $profitMargin = $this->final_amount > 0 
            ? (($this->final_amount - $totalCost) / $this->final_amount) * 100 
            : 0;

        $this->update([
            'material_cost' => $costs['material_cost'],
            'labor_cost' => $costs['labor_cost'],
            'overhead_cost' => $costs['overhead_cost'],
            'total_cost' => $totalCost,
            'shipping_cost' => $shipping['shipping_cost'],
            'shipping_country' => $shipping['shipping_country'],
            'order_weight' => $shipping['order_weight'],
            'profit_margin' => round($profitMargin, 2),
        ]);

        return $this->fresh();
    }
}
