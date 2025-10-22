<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WooCommerceSale extends Model
{
    use HasFactory;

    protected $table = 'woocommerce_sales';

    protected $fillable = [
        'wc_order_id',
        'order_id',
        'customer_email',
        'customer_name',
        'subtotal',
        'tax',
        'shipping',
        'discount',
        'total',
        'production_cost',
        'profit',
        'status',
        'payment_method',
        'order_date',
        'items',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'production_cost' => 'decimal:2',
        'profit' => 'decimal:2',
        'order_date' => 'datetime',
        'items' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

