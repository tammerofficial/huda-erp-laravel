<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Kuwait Textiles Co.',
                'email' => 'sales@kuwaittextiles.com',
                'phone' => '+965 2245 6789',
                'address' => 'Shuwaikh Industrial Area, Block 3, Kuwait',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'notes' => 'Main fabric supplier - Local delivery within 24h',
            ],
            [
                'name' => 'Arabian Fabrics Trading',
                'email' => 'info@arabianfabrics.com',
                'phone' => '+965 2256 7890',
                'address' => 'Rai Industrial Area, Street 42, Kuwait',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'notes' => 'Premium quality fabrics - Weekly deliveries',
            ],
            [
                'name' => 'Gulf Thread & Yarn Supplies',
                'email' => 'orders@gulfthread.com',
                'phone' => '+965 2267 8901',
                'address' => 'Sabhan Industrial Area, Kuwait',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'notes' => 'Thread and yarn specialist',
            ],
            [
                'name' => 'Al-Noor Buttons & Accessories',
                'email' => 'contact@alnoor-accessories.com',
                'phone' => '+965 2278 9012',
                'address' => 'Shuwaikh, Al-Tijaria Complex, Kuwait',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'notes' => 'Buttons, zippers, and accessories supplier',
            ],
            [
                'name' => 'International Fabrics Import',
                'email' => 'import@intfabrics.com',
                'phone' => '+971 4 555 6789',
                'address' => 'Textile Souk, Deira, Dubai, UAE',
                'city' => 'Dubai',
                'country' => 'United Arab Emirates',
                'notes' => 'International fabrics - 7-10 days delivery',
            ],
            [
                'name' => 'Premium Embroidery Supplies',
                'email' => 'sales@premiumembroidery.com',
                'phone' => '+965 2289 0123',
                'address' => 'Fahaheel Industrial Area, Kuwait',
                'city' => 'Fahaheel',
                'country' => 'Kuwait',
                'notes' => 'Embroidery threads and materials specialist',
            ],
            [
                'name' => 'Quality Packaging Solutions',
                'email' => 'info@qualitypackaging.com.kw',
                'phone' => '+965 2290 1234',
                'address' => 'Amghara Industrial Area, Kuwait',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'notes' => 'Packaging materials and boxes supplier',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']],
                $supplier
            );
        }

        $this->command->info('✅ ' . count($suppliers) . ' suppliers seeded successfully!');
    }
}

