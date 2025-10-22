<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'payment_gateway_id',
        'payable_type',
        'payable_id',
        'amount',
        'fee',
        'net_amount',
        'currency',
        'status',
        'reference_number',
        'response_data',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    public function payable()
    {
        return $this->morphTo();
    }
}

