<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;

class ShippingCalculator
{
    // GCC Countries
    const GCC_COUNTRIES = ['SA', 'AE', 'QA', 'BH', 'OM'];

    /**
     * Calculate shipping cost based on country and weight
     */
    public function calculateShippingCost(string $country, float $weight): float
    {
        $country = strtoupper($country);

        // Kuwait - flat rate
        if ($country === 'KW') {
            return $this->getKuwaitRate();
        }

        // GCC Countries
        if (in_array($country, self::GCC_COUNTRIES)) {
            return $this->calculateGCCShipping($weight);
        }

        // Other countries - configurable
        return $this->getInternationalRate($weight);
    }

    /**
     * Get Kuwait flat rate
     */
    protected function getKuwaitRate(): float
    {
        return (float) Setting::where('category', 'shipping')
            ->where('key', 'kuwait_flat_rate')
            ->value('value') ?? 2;
    }

    /**
     * Calculate GCC shipping based on weight
     */
    protected function calculateGCCShipping(float $weight): float
    {
        $baseRate = (float) Setting::where('category', 'shipping')
            ->where('key', 'gcc_base_rate')
            ->value('value') ?? 7;

        $weightThreshold = (float) Setting::where('category', 'shipping')
            ->where('key', 'gcc_weight_threshold')
            ->value('value') ?? 2;

        $additionalPerKg = (float) Setting::where('category', 'shipping')
            ->where('key', 'gcc_additional_per_kg')
            ->value('value') ?? 2;

        if ($weight <= $weightThreshold) {
            return $baseRate;
        }

        $additionalWeight = $weight - $weightThreshold;
        return $baseRate + (ceil($additionalWeight) * $additionalPerKg);
    }

    /**
     * Get international shipping rate
     */
    protected function getInternationalRate(float $weight): float
    {
        $baseRate = (float) Setting::where('category', 'shipping')
            ->where('key', 'international_base_rate')
            ->value('value') ?? 15;

        $perKg = (float) Setting::where('category', 'shipping')
            ->where('key', 'international_per_kg')
            ->value('value') ?? 5;

        return $baseRate + (ceil($weight) * $perKg);
    }

    /**
     * Get order total weight from order items
     */
    public function getOrderWeight(Order $order): float
    {
        $totalWeight = 0;

        foreach ($order->orderItems as $item) {
            if ($item->product && $item->product->weight) {
                $totalWeight += $item->product->weight * $item->quantity;
            }
        }

        return $totalWeight;
    }

    /**
     * Calculate and update order shipping cost
     */
    public function calculateOrderShipping(Order $order): array
    {
        $weight = $this->getOrderWeight($order);
        
        // Extract country from shipping address or use default
        $country = $order->shipping_country ?? $this->extractCountryFromAddress($order->shipping_address) ?? 'KW';

        $shippingCost = $this->calculateShippingCost($country, $weight);

        return [
            'shipping_cost' => round($shippingCost, 2),
            'shipping_country' => $country,
            'order_weight' => round($weight, 2),
        ];
    }

    /**
     * Extract country code from shipping address string
     */
    protected function extractCountryFromAddress(?string $address): ?string
    {
        if (!$address) {
            return null;
        }

        // Common country mappings
        $countryMappings = [
            'kuwait' => 'KW',
            'saudi' => 'SA',
            'saudi arabia' => 'SA',
            'uae' => 'AE',
            'emirates' => 'AE',
            'dubai' => 'AE',
            'abu dhabi' => 'AE',
            'qatar' => 'QA',
            'doha' => 'QA',
            'bahrain' => 'BH',
            'oman' => 'OM',
            'muscat' => 'OM',
        ];

        $addressLower = strtolower($address);

        foreach ($countryMappings as $keyword => $code) {
            if (str_contains($addressLower, $keyword)) {
                return $code;
            }
        }

        return null;
    }

    /**
     * Get shipping rates for all zones
     */
    public function getAllShippingRates(): array
    {
        return [
            'kuwait' => [
                'code' => 'KW',
                'name' => 'Kuwait',
                'type' => 'flat_rate',
                'rate' => $this->getKuwaitRate(),
            ],
            'gcc' => [
                'countries' => ['SA', 'AE', 'QA', 'BH', 'OM'],
                'names' => ['Saudi Arabia', 'UAE', 'Qatar', 'Bahrain', 'Oman'],
                'type' => 'weight_based',
                'base_rate' => (float) Setting::where('category', 'shipping')->where('key', 'gcc_base_rate')->value('value') ?? 7,
                'weight_threshold' => (float) Setting::where('category', 'shipping')->where('key', 'gcc_weight_threshold')->value('value') ?? 2,
                'additional_per_kg' => (float) Setting::where('category', 'shipping')->where('key', 'gcc_additional_per_kg')->value('value') ?? 2,
            ],
            'international' => [
                'type' => 'weight_based',
                'base_rate' => (float) Setting::where('category', 'shipping')->where('key', 'international_base_rate')->value('value') ?? 15,
                'per_kg' => (float) Setting::where('category', 'shipping')->where('key', 'international_per_kg')->value('value') ?? 5,
            ],
        ];
    }
}

