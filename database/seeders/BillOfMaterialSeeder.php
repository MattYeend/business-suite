<?php

namespace Database\Seeders;

use App\Models\BillOfMaterial;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class BillOfMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        // Sample BOMs for different product types
        $bomTemplates = [
            // Hardware Products
            [
                'product_name' => 'Laptop',
                'bom_number' => 'BOM-10001',
                'version' => '2.0',
                'description' => 'Complete assembly for standard business laptop',
                'is_active' => true,
            ],
            [
                'product_name' => 'Desktop Computer Workstation',
                'bom_number' => 'BOM-10002',
                'version' => '1.5',
                'description' => 'Desktop workstation complete assembly with peripherals',
                'is_active' => true,
            ],
            [
                'product_name' => 'Server Rack',
                'bom_number' => 'BOM-10003',
                'version' => '3.0',
                'description' => '42U server rack complete assembly',
                'is_active' => true,
            ],
            [
                'product_name' => 'Router',
                'bom_number' => 'BOM-10004',
                'version' => '1.0',
                'description' => 'Enterprise wireless router assembly',
                'is_active' => true,
            ],
            [
                'product_name' => 'Network Switch',
                'bom_number' => 'BOM-10005',
                'version' => '2.0',
                'description' => '24-port managed network switch assembly',
                'is_active' => true,
            ],
            [
                'product_name' => 'Printer',
                'bom_number' => 'BOM-10006',
                'version' => '1.8',
                'description' => 'Colour laser printer assembly with toner cartridges',
                'is_active' => true,
            ],
            [
                'product_name' => 'Scanner',
                'bom_number' => 'BOM-10007',
                'version' => '1.2',
                'description' => 'Document feeder scanner assembly',
                'is_active' => true,
            ],
            [
                'product_name' => 'Monitor',
                'bom_number' => 'BOM-10008',
                'version' => '2.5',
                'description' => '27" 4K monitor assembly with stand and cables',
                'is_active' => true,
            ],
            [
                'product_name' => 'Docking Station',
                'bom_number' => 'BOM-10009',
                'version' => '1.0',
                'description' => 'Universal docking station with multi-port connectivity',
                'is_active' => true,
            ],
            [
                'product_name' => 'UPS',
                'bom_number' => 'BOM-10010',
                'version' => '1.3',
                'description' => 'Uninterruptible power supply 1500VA assembly',
                'is_active' => true,
            ],
            [
                'product_name' => 'Keyboard',
                'bom_number' => 'BOM-10011',
                'version' => '1.0',
                'description' => 'Mechanical RGB keyboard assembly',
                'is_active' => true,
            ],
            [
                'product_name' => 'Webcam',
                'bom_number' => 'BOM-10012',
                'version' => '1.5',
                'description' => 'HD 1080p webcam with mounting hardware',
                'is_active' => true,
            ],
            [
                'product_name' => 'Headset',
                'bom_number' => 'BOM-10013',
                'version' => '2.0',
                'description' => 'Noise cancelling headset with boom microphone',
                'is_active' => true,
            ],
            [
                'product_name' => 'Access Point',
                'bom_number' => 'BOM-10014',
                'version' => '1.2',
                'description' => 'Wireless access point with mounting bracket',
                'is_active' => true,
            ],
            [
                'product_name' => 'External Hard Drive',
                'bom_number' => 'BOM-10015',
                'version' => '1.0',
                'description' => '4TB external hard drive with USB cable',
                'is_active' => true,
            ],

            // Electronics
            [
                'product_name' => 'Tablet',
                'bom_number' => 'BOM-10016',
                'version' => '2.1',
                'description' => '10" tablet assembly with accessories',
                'is_active' => true,
            ],
            [
                'product_name' => 'Smartphone',
                'bom_number' => 'BOM-10017',
                'version' => '3.0',
                'description' => 'Business smartphone complete assembly',
                'is_active' => true,
            ],
            [
                'product_name' => 'Projector',
                'bom_number' => 'BOM-10018',
                'version' => '1.5',
                'description' => 'Full HD projector with remote and cables',
                'is_active' => true,
            ],
            [
                'product_name' => 'Digital Camera',
                'bom_number' => 'BOM-10019',
                'version' => '2.0',
                'description' => '24MP digital camera with lens kit',
                'is_active' => true,
            ],
            [
                'product_name' => 'Smartwatch',
                'bom_number' => 'BOM-10020',
                'version' => '1.8',
                'description' => 'Fitness tracking smartwatch assembly',
                'is_active' => true,
            ],

            // Discontinued/Inactive BOMs
            [
                'product_name' => 'KVM Switch',
                'bom_number' => 'BOM-10021',
                'version' => '1.0',
                'description' => '4-port KVM switch (legacy model)',
                'is_active' => false,
                'effective_to' => now()->subMonths(6),
            ],
            [
                'product_name' => 'USB Hub',
                'bom_number' => 'BOM-10022',
                'version' => '1.0',
                'description' => '10-port USB hub (old version)',
                'is_active' => false,
                'effective_to' => now()->subMonths(3),
            ],
            [
                'product_name' => 'Mouse',
                'bom_number' => 'BOM-10023',
                'version' => '1.2',
                'description' => 'Wireless ergonomic mouse (discontinued)',
                'is_active' => false,
                'effective_to' => now()->subMonths(4),
            ],

            // Additional Active BOMs
            [
                'product_name' => 'Bluetooth Speaker',
                'bom_number' => 'BOM-10024',
                'version' => '1.5',
                'description' => 'Portable bluetooth speaker assembly',
                'is_active' => true,
            ],
            [
                'product_name' => 'Microphone',
                'bom_number' => 'BOM-10025',
                'version' => '1.0',
                'description' => 'USB condenser microphone with stand',
                'is_active' => true,
            ],
        ];

        $created = 0;

        foreach ($bomTemplates as $template) {
            // Try to find a product matching the template name, or use random
            $product = $products->first(function ($p) use ($template) {
                return stripos($p->name, $template['product_name']) !== false;
            }) ?? $products->random();

            $bomData = [
                'product_id' => $product->id,
                'bom_number' => $template['bom_number'],
                'version' => $template['version'],
                'description' => $template['description'],
                'is_active' => $template['is_active'],
                'effective_from' => now()->subMonths(rand(1, 12)),
                'effective_to' => $template['effective_to'] ?? null,
                'is_real' => true,
                'meta' => null,
                'created_by' => $users->random()->id,
            ];

            $bom = BillOfMaterial::firstOrCreate(
                [
                    'bom_number' => $template['bom_number'],
                ],
                $bomData
            );

            if ($bom->wasRecentlyCreated) {
                $created++;
            }
        }

        // Create additional random BOMs for products without them
        $productsWithoutBom = $products->filter(function ($product) {
            return $product->billOfMaterial()->count() === 0;
        })->take(5);

        foreach ($productsWithoutBom as $product) {
            $bomNumber = 'BOM-' . fake()->unique()->numberBetween(20000, 29999);
            
            BillOfMaterial::create([
                'product_id' => $product->id,
                'bom_number' => $bomNumber,
                'version' => '1.0',
                'description' => "Bill of materials for {$product->name}",
                'is_active' => true,
                'effective_from' => now()->subDays(rand(1, 90)),
                'effective_to' => null,
                'is_real' => true,
                'meta' => null,
                'created_by' => $users->random()->id,
            ]);
            
            $created++;
        }

        $this->command->info("Created {$created} bill of materials.");
    }
}
