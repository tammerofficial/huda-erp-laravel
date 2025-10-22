<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            // Quality Control Inspectors
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh.kumar@hudaerp.com',
                'position' => 'Quality Control Inspector',
                'department' => 'Quality Assurance',
                'salary' => 450.00,
                'phone' => '+965-9988-7766',
                'address' => 'Salmiya, Block 5, Street 12, Building 45',
                'skills' => ['Quality Inspection', 'Fabric Testing', 'ISO Standards', 'Defect Analysis'],
                'notes' => 'Senior quality inspector with 8 years experience',
            ],
            
            // Cutters (Fabric Cutters)
            [
                'name' => 'Amit Patel',
                'email' => 'amit.patel@hudaerp.com',
                'position' => 'Fabric Cutter',
                'department' => 'Cutting',
                'salary' => 320.00,
                'phone' => '+965-9877-6655',
                'address' => 'Farwaniya, Block 2, Street 8, Apartment 12',
                'skills' => ['Pattern Cutting', 'Fabric Layout', 'Cutting Machines', 'Measurement Accuracy'],
                'notes' => 'Expert in cutting complex patterns',
            ],
            [
                'name' => 'Suresh Sharma',
                'email' => 'suresh.sharma@hudaerp.com',
                'position' => 'Fabric Cutter',
                'department' => 'Cutting',
                'salary' => 310.00,
                'phone' => '+965-9766-5544',
                'address' => 'Jleeb Al-Shuyoukh, Block 3, Street 5',
                'skills' => ['Manual Cutting', 'Fabric Spreading', 'Pattern Reading', 'Precision Cutting'],
                'notes' => 'Specialized in traditional fabric cutting',
            ],
            [
                'name' => 'Vijay Reddy',
                'email' => 'vijay.reddy@hudaerp.com',
                'position' => 'Senior Fabric Cutter',
                'department' => 'Cutting',
                'salary' => 380.00,
                'phone' => '+965-9655-4433',
                'address' => 'Hawally, Block 4, Street 15, Building 22',
                'skills' => ['Advanced Cutting', 'CAD Software', 'Pattern Making', 'Team Leadership'],
                'notes' => '10 years experience, team leader',
            ],
            [
                'name' => 'Prakash Mehta',
                'email' => 'prakash.mehta@hudaerp.com',
                'position' => 'Fabric Cutter',
                'department' => 'Cutting',
                'salary' => 315.00,
                'phone' => '+965-9544-3322',
                'address' => 'Fahaheel, Block 1, Street 9, Apartment 5',
                'skills' => ['Bulk Cutting', 'Fabric Optimization', 'Quality Control', 'Speed Cutting'],
                'notes' => 'Fast and efficient cutter',
            ],
            
            // Tailors (Sewers/Stitchers)
            [
                'name' => 'Mohammed Ansari',
                'email' => 'mohammed.ansari@hudaerp.com',
                'position' => 'Master Tailor',
                'department' => 'Sewing',
                'salary' => 420.00,
                'phone' => '+965-9433-2211',
                'address' => 'Mangaf, Block 6, Street 3, Building 18',
                'skills' => ['Advanced Sewing', 'Custom Tailoring', 'Pattern Alteration', 'Quality Finishing'],
                'notes' => 'Master tailor with specialty in traditional garments',
            ],
            [
                'name' => 'Anil Kumar',
                'email' => 'anil.kumar@hudaerp.com',
                'position' => 'Tailor',
                'department' => 'Sewing',
                'salary' => 340.00,
                'phone' => '+965-9322-1100',
                'address' => 'Salmiya, Block 8, Street 11, Apartment 8',
                'skills' => ['Industrial Sewing', 'Machine Operation', 'Seam Finishing', 'Speed Sewing'],
                'notes' => 'Efficient production line tailor',
            ],
            [
                'name' => 'Ravi Shankar',
                'email' => 'ravi.shankar@hudaerp.com',
                'position' => 'Tailor',
                'department' => 'Sewing',
                'salary' => 335.00,
                'phone' => '+965-9211-0099',
                'address' => 'Farwaniya, Block 5, Street 7, Building 30',
                'skills' => ['Straight Stitch', 'Overlocking', 'Buttonholes', 'Zipper Installation'],
                'notes' => 'Specialized in finishing work',
            ],
            [
                'name' => 'Deepak Singh',
                'email' => 'deepak.singh@hudaerp.com',
                'position' => 'Senior Tailor',
                'department' => 'Sewing',
                'salary' => 390.00,
                'phone' => '+965-9100-9988',
                'address' => 'Khaitan, Block 2, Street 14, Apartment 15',
                'skills' => ['Complex Stitching', 'Embroidery', 'Quality Inspection', 'Training'],
                'notes' => 'Trains new tailors, excellent quality work',
            ],
            [
                'name' => 'Sanjay Gupta',
                'email' => 'sanjay.gupta@hudaerp.com',
                'position' => 'Tailor',
                'department' => 'Sewing',
                'salary' => 330.00,
                'phone' => '+965-9099-8877',
                'address' => 'Jleeb Al-Shuyoukh, Block 7, Street 4',
                'skills' => ['Basic Sewing', 'Machine Maintenance', 'Pattern Following', 'Garment Assembly'],
                'notes' => 'Reliable and consistent work',
            ],
            [
                'name' => 'Manoj Verma',
                'email' => 'manoj.verma@hudaerp.com',
                'position' => 'Tailor',
                'department' => 'Sewing',
                'salary' => 345.00,
                'phone' => '+965-8988-7766',
                'address' => 'Farwaniya, Block 1, Street 20, Building 8',
                'skills' => ['Industrial Machines', 'Speed Work', 'Quality Control', 'Seam Pressing'],
                'notes' => 'Fast worker with good attention to detail',
            ],
            [
                'name' => 'Ramesh Joshi',
                'email' => 'ramesh.joshi@hudaerp.com',
                'position' => 'Tailor',
                'department' => 'Sewing',
                'salary' => 338.00,
                'phone' => '+965-8877-6655',
                'address' => 'Hawally, Block 9, Street 6, Apartment 20',
                'skills' => ['Garment Construction', 'Alterations', 'Hem Work', 'Sleeve Setting'],
                'notes' => 'Excellent at garment alterations',
            ],
            
            // Shakkak (Embroiderers/Detail Workers)
            [
                'name' => 'Arjun Kapoor',
                'email' => 'arjun.kapoor@hudaerp.com',
                'position' => 'Embroidery Specialist',
                'department' => 'Embroidery',
                'salary' => 370.00,
                'phone' => '+965-8766-5544',
                'address' => 'Salmiya, Block 10, Street 18, Building 5',
                'skills' => ['Hand Embroidery', 'Machine Embroidery', 'Beading', 'Sequin Work'],
                'notes' => 'Master embroiderer, handles luxury items',
            ],
            [
                'name' => 'Kiran Desai',
                'email' => 'kiran.desai@hudaerp.com',
                'position' => 'Detail Worker',
                'department' => 'Embroidery',
                'salary' => 350.00,
                'phone' => '+965-8655-4433',
                'address' => 'Fahaheel, Block 3, Street 12, Apartment 9',
                'skills' => ['Decorative Stitching', 'Appliqué', 'Thread Work', 'Pattern Creation'],
                'notes' => 'Creative detail work on high-end garments',
            ],
            [
                'name' => 'Naveen Malhotra',
                'email' => 'naveen.malhotra@hudaerp.com',
                'position' => 'Embroidery Technician',
                'department' => 'Embroidery',
                'salary' => 360.00,
                'phone' => '+965-8544-3322',
                'address' => 'Mangaf, Block 4, Street 7, Building 12',
                'skills' => ['CAD Embroidery', 'Machine Programming', 'Quality Check', 'Design Digitizing'],
                'notes' => 'Technical expert in computerized embroidery',
            ],
            [
                'name' => 'Ashok Pandey',
                'email' => 'ashok.pandey@hudaerp.com',
                'position' => 'Hand Embroidery Artist',
                'department' => 'Embroidery',
                'salary' => 385.00,
                'phone' => '+965-8433-2211',
                'address' => 'Salmiya, Block 11, Street 22, Apartment 7',
                'skills' => ['Traditional Embroidery', 'Zardozi Work', 'Stone Setting', 'Custom Designs'],
                'notes' => 'Specialized in traditional Indian embroidery styles',
            ],
            
            // Support Staff
            [
                'name' => 'Ganesh Nair',
                'email' => 'ganesh.nair@hudaerp.com',
                'position' => 'Production Supervisor',
                'department' => 'Production',
                'salary' => 480.00,
                'phone' => '+965-8322-1100',
                'address' => 'Farwaniya, Block 8, Street 16, Building 25',
                'skills' => ['Team Management', 'Production Planning', 'Quality Control', 'Process Optimization'],
                'notes' => 'Oversees production floor operations',
            ],
            [
                'name' => 'Harish Rao',
                'email' => 'harish.rao@hudaerp.com',
                'position' => 'Warehouse Assistant',
                'department' => 'Warehouse',
                'salary' => 280.00,
                'phone' => '+965-8211-0099',
                'address' => 'Jleeb Al-Shuyoukh, Block 5, Street 9',
                'skills' => ['Inventory Management', 'Material Handling', 'Stock Counting', 'Organization'],
                'notes' => 'Handles fabric and material storage',
            ],
            [
                'name' => 'Dinesh Choudhary',
                'email' => 'dinesh.choudhary@hudaerp.com',
                'position' => 'Maintenance Technician',
                'department' => 'Maintenance',
                'salary' => 350.00,
                'phone' => '+965-8100-9988',
                'address' => 'Khaitan, Block 6, Street 13, Building 40',
                'skills' => ['Machine Repair', 'Electrical Work', 'Preventive Maintenance', 'Troubleshooting'],
                'notes' => 'Maintains all sewing and cutting machines',
            ],
            [
                'name' => 'Yogesh Tripathi',
                'email' => 'yogesh.tripathi@hudaerp.com',
                'position' => 'Packing Specialist',
                'department' => 'Finishing',
                'salary' => 290.00,
                'phone' => '+965-7999-8877',
                'address' => 'Fahaheel, Block 7, Street 5, Apartment 18',
                'skills' => ['Quality Packing', 'Pressing', 'Labeling', 'Order Fulfillment'],
                'notes' => 'Final quality check and packing',
            ],
        ];

        foreach ($employees as $index => $empData) {
            // Create user first
            $user = User::create([
                'name' => $empData['name'],
                'email' => $empData['email'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);

            // Create employee record
            Employee::create([
                'user_id' => $user->id,
                'employee_id' => 'EMP' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'phone' => $empData['phone'],
                'address' => $empData['address'],
                'position' => $empData['position'],
                'department' => $empData['department'],
                'salary' => $empData['salary'],
                'hire_date' => now()->subMonths(rand(3, 60)),
                'birth_date' => now()->subYears(rand(25, 50))->subMonths(rand(1, 11)),
                'employment_status' => 'active',
                'qr_code' => 'QR-' . strtoupper(Str::random(8)),
                'skills' => $empData['skills'],
                'notes' => $empData['notes'],
            ]);
        }

        $this->command->info('✅ 20 diverse employees seeded successfully with Indian names!');
        $this->command->info('📊 Breakdown:');
        $this->command->info('   - Quality Control: 1');
        $this->command->info('   - Fabric Cutters: 4');
        $this->command->info('   - Tailors: 7');
        $this->command->info('   - Embroidery Specialists: 4');
        $this->command->info('   - Support Staff: 4');
    }
}

