<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    public function run()
    {
        $gateways = [
            [
                'name' => 'KNET',
                'type' => 'debit_card',
                'provider' => 'Kuwait Payment Gateway',
                'transaction_fee_percentage' => 2.5,
                'transaction_fee_fixed' => 0.250,
                'is_active' => true,
                'settings' => [
                    'currency' => 'KWD',
                    'country' => 'Kuwait',
                ],
            ],
            [
                'name' => 'Visa',
                'type' => 'credit_card',
                'provider' => 'Visa International',
                'transaction_fee_percentage' => 2.9,
                'transaction_fee_fixed' => 0.300,
                'is_active' => true,
                'settings' => [
                    'currency' => 'KWD',
                    'international' => true,
                ],
            ],
            [
                'name' => 'MasterCard',
                'type' => 'credit_card',
                'provider' => 'MasterCard International',
                'transaction_fee_percentage' => 2.9,
                'transaction_fee_fixed' => 0.300,
                'is_active' => true,
                'settings' => [
                    'currency' => 'KWD',
                    'international' => true,
                ],
            ],
            [
                'name' => 'American Express',
                'type' => 'credit_card',
                'provider' => 'American Express',
                'transaction_fee_percentage' => 3.5,
                'transaction_fee_fixed' => 0.350,
                'is_active' => true,
                'settings' => [
                    'currency' => 'KWD',
                    'international' => true,
                ],
            ],
            [
                'name' => 'Cash on Delivery',
                'type' => 'cash',
                'provider' => 'Internal',
                'transaction_fee_percentage' => 0,
                'transaction_fee_fixed' => 0,
                'is_active' => true,
                'settings' => [
                    'currency' => 'KWD',
                ],
            ],
            [
                'name' => 'Bank Transfer',
                'type' => 'bank_transfer',
                'provider' => 'Local Banks',
                'transaction_fee_percentage' => 0,
                'transaction_fee_fixed' => 0,
                'is_active' => true,
                'settings' => [
                    'currency' => 'KWD',
                ],
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::updateOrCreate(
                ['name' => $gateway['name']],
                $gateway
            );
        }
    }
}

