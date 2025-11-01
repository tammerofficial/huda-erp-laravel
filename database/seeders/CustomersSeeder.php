<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Boutique Al-Zahra',
                'email' => 'contact@alzahra.com.kw',
                'phone' => '+965 9999 1111',
                'address' => 'Salmiya, Block 5, Street 10, Kuwait',
                'city' => 'Salmiya',
                'country' => 'Kuwait',
                'notes' => 'Premium boutique client - Regular bulk orders',
            ],
            [
                'name' => 'Fashion House Kuwait',
                'email' => 'orders@fashionkw.com',
                'phone' => '+965 9999 2222',
                'address' => 'Hawally, Salem Al-Mubarak Street, Kuwait',
                'city' => 'Hawally',
                'country' => 'Kuwait',
                'notes' => 'Wholesale client - Monthly orders',
            ],
            [
                'name' => 'Elegant Abayas Store',
                'email' => 'info@elegantabayas.com',
                'phone' => '+965 9999 3333',
                'address' => 'Fahaheel, Al-Manshar Mall, Kuwait',
                'city' => 'Fahaheel',
                'country' => 'Kuwait',
                'notes' => 'Specializes in luxury abayas',
            ],
            [
                'name' => 'Gulf Textiles Trading',
                'email' => 'sales@gulftextiles.com',
                'phone' => '+966 55 123 4567',
                'address' => 'Riyadh, Al Olaya District, Saudi Arabia',
                'city' => 'Riyadh',
                'country' => 'Saudi Arabia',
                'notes' => 'GCC wholesale distributor',
            ],
            [
                'name' => 'Modern Fashion LLC',
                'email' => 'info@modernfashion.ae',
                'phone' => '+971 50 999 8888',
                'address' => 'Dubai, Trade Center Area, UAE',
                'city' => 'Dubai',
                'country' => 'United Arab Emirates',
                'notes' => 'International client - Ships to Dubai',
            ],
            [
                'name' => 'Sara Al-Abdullah',
                'email' => 'sara.abdullah@gmail.com',
                'phone' => '+965 9888 7777',
                'address' => 'Jabriya, Block 1A, Street 5, Kuwait',
                'city' => 'Jabriya',
                'country' => 'Kuwait',
                'notes' => 'Individual customer - Frequent orders',
            ],
            [
                'name' => 'Noura Fashion Boutique',
                'email' => 'noura@nourafashion.com',
                'phone' => '+965 9777 6666',
                'address' => 'Fintas, Coastal Road, Kuwait',
                'city' => 'Fintas',
                'country' => 'Kuwait',
                'notes' => 'Retail client - Premium quality',
            ],
            [
                'name' => 'Al-Rashid Trading Co.',
                'email' => 'trading@rashid.com.kw',
                'phone' => '+965 2234 5678',
                'address' => 'Kuwait City, Sharq, Commercial Area, Kuwait',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'notes' => 'Corporate client - Bulk orders for uniforms',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }

        $this->command->info('✅ ' . count($customers) . ' customers seeded successfully!');
    }
}

