<?php

namespace Database\Seeders;

use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialItem;
use App\Models\Part;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class BillOfMaterialItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (BillOfMaterialItem::exists()) {
            $this->command->info('BOM items already seeded, skipping...');
            return;
        }

        $users = User::all();
        $boms = BillOfMaterial::where('is_active', true)->get();
        $parts = Part::all();
        $products = Product::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        if ($boms->isEmpty()) {
            $this->command->warn('No BOMs found. Please run BillOfMaterialSeeder first.');
            return;
        }

        if ($parts->isEmpty()) {
            $this->command->warn('No parts found. Please run PartSeeder first.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        $created = 0;

        // Define common BOM item templates for different product types
        $itemTemplates = [
            // Laptop BOM items
            'Laptop' => [
                ['part_name' => 'CPU', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Motherboard', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'RAM', 'quantity' => 2, 'sequence' => 3, 'is_optional' => false, 'notes' => '8GB modules'],
                ['part_name' => 'SSD', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false, 'notes' => '512GB minimum'],
                ['part_name' => 'Display', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
                ['part_name' => 'Battery', 'quantity' => 1, 'sequence' => 6, 'is_optional' => false],
                ['part_name' => 'Keyboard', 'quantity' => 1, 'sequence' => 7, 'is_optional' => false],
                ['part_name' => 'Trackpad', 'quantity' => 1, 'sequence' => 8, 'is_optional' => false],
                ['part_name' => 'Webcam', 'quantity' => 1, 'sequence' => 9, 'is_optional' => false],
                ['part_name' => 'WiFi Card', 'quantity' => 1, 'sequence' => 10, 'is_optional' => false],
                ['part_name' => 'Carrying Case', 'quantity' => 1, 'sequence' => 11, 'is_optional' => true],
            ],
            
            // Desktop Computer Workstation BOM items
            'Desktop Computer Workstation' => [
                ['part_name' => 'CPU', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Motherboard', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'RAM', 'quantity' => 4, 'sequence' => 3, 'is_optional' => false, 'notes' => '16GB modules'],
                ['part_name' => 'SSD', 'quantity' => 2, 'sequence' => 4, 'is_optional' => false, 'notes' => '1TB each'],
                ['part_name' => 'GPU', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
                ['part_name' => 'Power Supply', 'quantity' => 1, 'sequence' => 6, 'is_optional' => false],
                ['part_name' => 'Case', 'quantity' => 1, 'sequence' => 7, 'is_optional' => false],
                ['part_name' => 'Cooling Fan', 'quantity' => 3, 'sequence' => 8, 'is_optional' => false],
                ['part_name' => 'Keyboard', 'quantity' => 1, 'sequence' => 9, 'is_optional' => false],
                ['part_name' => 'Mouse', 'quantity' => 1, 'sequence' => 10, 'is_optional' => false],
            ],

            // Server Rack BOM items
            'Server Rack' => [
                ['part_name' => 'Rack Frame', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Rack Rails', 'quantity' => 2, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'PDU', 'quantity' => 2, 'sequence' => 3, 'is_optional' => false, 'notes' => 'Power distribution unit'],
                ['part_name' => 'Cable Management', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Rack Shelf', 'quantity' => 4, 'sequence' => 5, 'is_optional' => true],
                ['part_name' => 'Cooling Fan', 'quantity' => 4, 'sequence' => 6, 'is_optional' => false],
            ],

            // Router BOM items
            'Router' => [
                ['part_name' => 'Mainboard', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Antenna', 'quantity' => 4, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'Power Adapter', 'quantity' => 1, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'Ethernet Cable', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Mounting Bracket', 'quantity' => 1, 'sequence' => 5, 'is_optional' => true],
            ],

            // Monitor BOM items
            'Monitor' => [
                ['part_name' => 'Display Panel', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Stand', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'Power Cable', 'quantity' => 1, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'HDMI Cable', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'DisplayPort Cable', 'quantity' => 1, 'sequence' => 5, 'is_optional' => true],
            ],

            // Printer BOM items
            'Printer' => [
                ['part_name' => 'Print Engine', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Toner Cartridge', 'quantity' => 4, 'sequence' => 2, 'is_optional' => false, 'notes' => 'CMYK set'],
                ['part_name' => 'Paper Tray', 'quantity' => 2, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'Power Cable', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'USB Cable', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
            ],

            // Smartphone BOM items
            'Smartphone' => [
                ['part_name' => 'Display', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Battery', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'Camera Module', 'quantity' => 3, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'Mainboard', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Charging Cable', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
                ['part_name' => 'Protective Case', 'quantity' => 1, 'sequence' => 6, 'is_optional' => true],
            ],

            // Tablet BOM items
            'Tablet' => [
                ['part_name' => 'Display', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Battery', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'Mainboard', 'quantity' => 1, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'Camera Module', 'quantity' => 2, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Charging Cable', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
                ['part_name' => 'Stylus', 'quantity' => 1, 'sequence' => 6, 'is_optional' => true],
            ],

            // Network Switch BOM items
            'Network Switch' => [
                ['part_name' => 'Mainboard', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Port Module', 'quantity' => 24, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'Power Supply', 'quantity' => 1, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'Cooling Fan', 'quantity' => 2, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Rack Mount Kit', 'quantity' => 1, 'sequence' => 5, 'is_optional' => true],
            ],

            // Docking Station BOM items
            'Docking Station' => [
                ['part_name' => 'Main PCB', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'USB-C Port', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'USB-A Port', 'quantity' => 4, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'HDMI Port', 'quantity' => 2, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Ethernet Port', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
                ['part_name' => 'Power Adapter', 'quantity' => 1, 'sequence' => 6, 'is_optional' => false],
            ],

            // UPS BOM items
            'UPS' => [
                ['part_name' => 'Battery', 'quantity' => 2, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Inverter', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'Control Board', 'quantity' => 1, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'Power Cable', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Display Panel', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
            ],

            // Webcam BOM items
            'Webcam' => [
                ['part_name' => 'Camera Sensor', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Lens', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'Microphone', 'quantity' => 2, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'USB Cable', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Mounting Clip', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
            ],

            // Headset BOM items
            'Headset' => [
                ['part_name' => 'Speaker Driver', 'quantity' => 2, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Ear Cushion', 'quantity' => 2, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'Boom Microphone', 'quantity' => 1, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'Headband', 'quantity' => 1, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Cable', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
            ],

            // Projector BOM items
            'Projector' => [
                ['part_name' => 'Lamp', 'quantity' => 1, 'sequence' => 1, 'is_optional' => false],
                ['part_name' => 'Lens', 'quantity' => 1, 'sequence' => 2, 'is_optional' => false],
                ['part_name' => 'DMD Chip', 'quantity' => 1, 'sequence' => 3, 'is_optional' => false],
                ['part_name' => 'Cooling Fan', 'quantity' => 2, 'sequence' => 4, 'is_optional' => false],
                ['part_name' => 'Remote Control', 'quantity' => 1, 'sequence' => 5, 'is_optional' => false],
                ['part_name' => 'HDMI Cable', 'quantity' => 1, 'sequence' => 6, 'is_optional' => false],
            ],
        ];

        foreach ($boms as $bom) {
            $product = $bom->product;
            
            // Try to find matching template based on product name
            $template = null;
            foreach ($itemTemplates as $productType => $items) {
                if (stripos($product->name, $productType) !== false) {
                    $template = $items;
                    break;
                }
            }

            // If no template found, create generic items
            if (!$template) {
                $itemCount = rand(3, 8);
                for ($i = 1; $i <= $itemCount; $i++) {
                    BillOfMaterialItem::create([
                        'bill_of_material_id' => $bom->id,
                        'product_id' => $product->id,
                        'part_id' => $parts->random()->id,
                        'quantity' => round(rand(5, 100) / 10, 4),
                        'sequence' => $i,
                        'notes' => rand(1, 10) <= 3 ? 'Standard component' : null,
                        'is_optional' => rand(1, 100) <= 15,
                        'is_real' => true,
                        'meta' => null,
                        'created_by' => $users->random()->id,
                    ]);
                    $created++;
                }
            } else {
                // Use template to create items
                foreach ($template as $itemData) {
                    // Try to find a matching part
                    $part = $parts->first(function ($p) use ($itemData) {
                        return stripos($p->name, $itemData['part_name']) !== false;
                    }) ?? $parts->random();

                    BillOfMaterialItem::create([
                        'bill_of_material_id' => $bom->id,
                        'product_id' => $product->id,
                        'part_id' => $part->id,
                        'quantity' => $itemData['quantity'],
                        'sequence' => $itemData['sequence'],
                        'notes' => $itemData['notes'] ?? null,
                        'is_optional' => $itemData['is_optional'],
                        'is_real' => true,
                        'meta' => null,
                        'created_by' => $users->random()->id,
                    ]);
                    $created++;
                }
            }
        }

        $this->command->info("Created {$created} bill of material items.");
    }
}
