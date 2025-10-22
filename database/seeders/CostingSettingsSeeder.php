<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class CostingSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Costing settings
            [
                'category' => 'costing',
                'key' => 'labor_cost_percentage',
                'value' => '30',
                'type' => 'number',
                'description' => 'Default labor cost as percentage of material cost',
            ],
            [
                'category' => 'costing',
                'key' => 'overhead_cost_percentage',
                'value' => '20',
                'type' => 'number',
                'description' => 'Default overhead cost as percentage of material cost',
            ],
            [
                'category' => 'costing',
                'key' => 'profit_margin_target',
                'value' => '40',
                'type' => 'number',
                'description' => 'Target profit margin percentage for suggested pricing',
            ],
            
            // Shipping settings
            [
                'category' => 'shipping',
                'key' => 'kuwait_flat_rate',
                'value' => '2',
                'type' => 'number',
                'description' => 'Flat shipping rate for Kuwait (KWD)',
            ],
            [
                'category' => 'shipping',
                'key' => 'gcc_base_rate',
                'value' => '7',
                'type' => 'number',
                'description' => 'Base shipping rate for GCC countries (KWD)',
            ],
            [
                'category' => 'shipping',
                'key' => 'gcc_additional_per_kg',
                'value' => '2',
                'type' => 'number',
                'description' => 'Additional shipping cost per kg for GCC over threshold (KWD)',
            ],
            [
                'category' => 'shipping',
                'key' => 'gcc_weight_threshold',
                'value' => '2',
                'type' => 'number',
                'description' => 'Weight threshold for GCC base rate (kg)',
            ],
            [
                'category' => 'shipping',
                'key' => 'international_base_rate',
                'value' => '15',
                'type' => 'number',
                'description' => 'Base shipping rate for international orders (KWD)',
            ],
            [
                'category' => 'shipping',
                'key' => 'international_per_kg',
                'value' => '5',
                'type' => 'number',
                'description' => 'Additional shipping cost per kg for international orders (KWD)',
            ],
            
            // Payroll settings
            [
                'category' => 'payroll',
                'key' => 'overtime_multiplier',
                'value' => '1.5',
                'type' => 'number',
                'description' => 'Overtime payment multiplier (e.g., 1.5 = time and a half)',
            ],
            [
                'category' => 'payroll',
                'key' => 'working_days_per_month',
                'value' => '26',
                'type' => 'number',
                'description' => 'Standard working days per month',
            ],
            [
                'category' => 'payroll',
                'key' => 'working_hours_per_day',
                'value' => '8',
                'type' => 'number',
                'description' => 'Standard working hours per day',
            ],
            [
                'category' => 'payroll',
                'key' => 'payment_day',
                'value' => '25',
                'type' => 'number',
                'description' => 'Day of month for salary payment',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                [
                    'category' => $setting['category'],
                    'key' => $setting['key'],
                ],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'description' => $setting['description'],
                ]
            );
        }

        $this->command->info('Costing, Shipping, and Payroll settings seeded successfully!');
    }
}
