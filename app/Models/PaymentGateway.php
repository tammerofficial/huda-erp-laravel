<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'provider',
        'transaction_fee_percentage',
        'transaction_fee_fixed',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'transaction_fee_percentage' => 'decimal:2',
        'transaction_fee_fixed' => 'decimal:2',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function calculateFee($amount)
    {
        $percentageFee = ($amount * $this->transaction_fee_percentage) / 100;
        return $percentageFee + $this->transaction_fee_fixed;
    }
}

