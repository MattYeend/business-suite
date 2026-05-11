<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Part::truncate();

        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $parts = [];

        // Raw Materials (15 parts)
        $rawMaterials = [
            ['name' => 'Steel Sheet 1mm', 'material' => 'Steel', 'unit' => 'sheet', 'cost' => 45.00, 'price' => 67.50],
            ['name' => 'Aluminium Bar 50mm', 'material' => 'Aluminium', 'unit' => 'metre', 'cost' => 12.50, 'price' => 18.75],
            ['name' => 'Copper Wire 2.5mm', 'material' => 'Copper', 'unit' => 'metre', 'cost' => 1.80, 'price' => 2.70],
            ['name' => 'Plastic Pellets HDPE', 'material' => 'Plastic', 'unit' => 'kg', 'cost' => 2.20, 'price' => 3.30],
            ['name' => 'Brass Rod 25mm', 'material' => 'Brass', 'unit' => 'metre', 'cost' => 18.00, 'price' => 27.00],
            ['name' => 'Timber Oak Planks', 'material' => 'Wood', 'unit' => 'metre', 'cost' => 35.00, 'price' => 52.50],
            ['name' => 'Stainless Steel 304', 'material' => 'Steel', 'unit' => 'sheet', 'cost' => 89.00, 'price' => 133.50],
            ['name' => 'PVC Pipe 110mm', 'material' => 'Plastic', 'unit' => 'metre', 'cost' => 8.50, 'price' => 12.75],
            ['name' => 'Carbon Fibre Sheet', 'material' => 'Composite', 'unit' => 'sheet', 'cost' => 125.00, 'price' => 187.50],
            ['name' => 'Rubber Gasket Material', 'material' => 'Rubber', 'unit' => 'metre', 'cost' => 6.50, 'price' => 9.75],
            ['name' => 'Galvanised Steel Wire', 'material' => 'Steel', 'unit' => 'kg', 'cost' => 4.20, 'price' => 6.30],
            ['name' => 'Acrylic Sheet 5mm', 'material' => 'Plastic', 'unit' => 'sheet', 'cost' => 28.00, 'price' => 42.00],
            ['name' => 'Bronze Bar 30mm', 'material' => 'Brass', 'unit' => 'metre', 'cost' => 22.00, 'price' => 33.00],
            ['name' => 'MDF Board 18mm', 'material' => 'Wood', 'unit' => 'sheet', 'cost' => 24.00, 'price' => 36.00],
            ['name' => 'Silicone Rubber', 'material' => 'Rubber', 'unit' => 'kg', 'cost' => 15.00, 'price' => 22.50],
        ];

        foreach ($rawMaterials as $index => $material) {
            $parts[] = $this->createPartData(
                sku: 'RM-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $material['name'],
                type: 'raw_material',
                material: $material['material'],
                unitOfMeasure: $material['unit'],
                costPrice: $material['cost'],
                price: $material['price'],
                quantity: rand(50, 500),
                minStock: 20,
                maxStock: 600,
                users: $users
            );
        }

        // Finished Goods (20 parts)
        $finishedGoods = [
            ['name' => 'Electric Motor 3kW', 'brand' => 'Siemens', 'cost' => 285.00, 'price' => 427.50],
            ['name' => 'Hydraulic Pump HP-200', 'brand' => 'Bosch', 'cost' => 495.00, 'price' => 742.50],
            ['name' => 'Control Panel CP-1000', 'brand' => 'Schneider', 'cost' => 650.00, 'price' => 975.00],
            ['name' => 'Gearbox GB-450', 'brand' => 'SEW', 'cost' => 380.00, 'price' => 570.00],
            ['name' => 'Servo Drive SD-100', 'brand' => 'Fanuc', 'cost' => 1200.00, 'price' => 1800.00],
            ['name' => 'Linear Actuator LA-500', 'brand' => 'THK', 'cost' => 420.00, 'price' => 630.00],
            ['name' => 'Pressure Sensor PS-25', 'brand' => 'Honeywell', 'cost' => 125.00, 'price' => 187.50],
            ['name' => 'Temperature Controller TC-300', 'brand' => 'Omron', 'cost' => 245.00, 'price' => 367.50],
            ['name' => 'Pneumatic Cylinder PC-80', 'brand' => 'Festo', 'cost' => 165.00, 'price' => 247.50],
            ['name' => 'Safety Relay SR-10', 'brand' => 'Pilz', 'cost' => 95.00, 'price' => 142.50],
            ['name' => 'Frequency Inverter FI-7.5', 'brand' => 'ABB', 'cost' => 580.00, 'price' => 870.00],
            ['name' => 'Encoder EN-1024', 'brand' => 'Heidenhain', 'cost' => 320.00, 'price' => 480.00],
            ['name' => 'Circuit Breaker CB-63A', 'brand' => 'Eaton', 'cost' => 45.00, 'price' => 67.50],
            ['name' => 'Contactor CT-65A', 'brand' => 'Siemens', 'cost' => 68.00, 'price' => 102.00],
            ['name' => 'Proximity Switch PX-5mm', 'brand' => 'Turck', 'cost' => 42.00, 'price' => 63.00],
            ['name' => 'Solenoid Valve SV-24VDC', 'brand' => 'Burkert', 'cost' => 135.00, 'price' => 202.50],
            ['name' => 'Flow Meter FM-100L', 'brand' => 'Endress+Hauser', 'cost' => 485.00, 'price' => 727.50],
            ['name' => 'Ball Bearing 6205-2RS', 'brand' => 'SKF', 'cost' => 12.50, 'price' => 18.75],
            ['name' => 'Timing Belt TB-1500', 'brand' => 'Gates', 'cost' => 28.00, 'price' => 42.00],
            ['name' => 'Coupling CL-40', 'brand' => 'Lovejoy', 'cost' => 55.00, 'price' => 82.50],
        ];

        foreach ($finishedGoods as $index => $good) {
            $parts[] = $this->createPartData(
                sku: 'FG-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $good['name'],
                type: 'finished_good',
                brand: $good['brand'],
                costPrice: $good['cost'],
                price: $good['price'],
                quantity: rand(10, 100),
                minStock: 5,
                maxStock: 150,
                users: $users
            );
        }

        // Consumables (15 parts)
        $consumables = [
            ['name' => 'Cutting Oil 5L', 'cost' => 18.50, 'price' => 27.75],
            ['name' => 'Cleaning Rags Box/100', 'cost' => 12.00, 'price' => 18.00],
            ['name' => 'Cable Ties 200mm/100', 'cost' => 4.50, 'price' => 6.75],
            ['name' => 'Lubricating Grease 400g', 'cost' => 8.50, 'price' => 12.75],
            ['name' => 'Sandpaper Assorted/50', 'cost' => 15.00, 'price' => 22.50],
            ['name' => 'Masking Tape 50mm', 'cost' => 2.80, 'price' => 4.20],
            ['name' => 'Drill Bits HSS Set', 'cost' => 35.00, 'price' => 52.50],
            ['name' => 'Safety Gloves Pair', 'cost' => 3.20, 'price' => 4.80],
            ['name' => 'Wire Brush Set/3', 'cost' => 9.50, 'price' => 14.25],
            ['name' => 'Threadlock Blue 50ml', 'cost' => 6.80, 'price' => 10.20],
            ['name' => 'Electrical Tape Black', 'cost' => 1.50, 'price' => 2.25],
            ['name' => 'Zip Lock Bags 100/pk', 'cost' => 5.50, 'price' => 8.25],
            ['name' => 'Marker Pen Permanent', 'cost' => 1.20, 'price' => 1.80],
            ['name' => 'Cable Labels 100/sheet', 'cost' => 8.00, 'price' => 12.00],
            ['name' => 'Degreaser Spray 500ml', 'cost' => 7.50, 'price' => 11.25],
        ];

        foreach ($consumables as $index => $consumable) {
            $parts[] = $this->createPartData(
                sku: 'CON-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $consumable['name'],
                type: 'consumable',
                costPrice: $consumable['cost'],
                price: $consumable['price'],
                quantity: rand(20, 200),
                minStock: 10,
                maxStock: 300,
                users: $users
            );
        }

        // Spare Parts (15 parts)
        $spareParts = [
            ['name' => 'Motor Bearing Set', 'cost' => 45.00, 'price' => 67.50],
            ['name' => 'Hydraulic Seal Kit', 'cost' => 32.00, 'price' => 48.00],
            ['name' => 'Control Panel Fuse 10A', 'cost' => 2.50, 'price' => 3.75],
            ['name' => 'Gearbox Oil Seal', 'cost' => 8.50, 'price' => 12.75],
            ['name' => 'Servo Drive Fan', 'cost' => 28.00, 'price' => 42.00],
            ['name' => 'Actuator End Cap', 'cost' => 15.00, 'price' => 22.50],
            ['name' => 'Sensor Mounting Bracket', 'cost' => 6.50, 'price' => 9.75],
            ['name' => 'Controller Battery', 'cost' => 12.00, 'price' => 18.00],
            ['name' => 'Cylinder Rod Seal', 'cost' => 18.50, 'price' => 27.75],
            ['name' => 'Relay Contact Set', 'cost' => 22.00, 'price' => 33.00],
            ['name' => 'Inverter Cooling Fan', 'cost' => 35.00, 'price' => 52.50],
            ['name' => 'Encoder Connector', 'cost' => 14.50, 'price' => 21.75],
            ['name' => 'Breaker Auxiliary Switch', 'cost' => 24.00, 'price' => 36.00],
            ['name' => 'Contactor Coil 230V', 'cost' => 18.00, 'price' => 27.00],
            ['name' => 'Valve Diaphragm', 'cost' => 26.00, 'price' => 39.00],
        ];

        foreach ($spareParts as $index => $spare) {
            $parts[] = $this->createPartData(
                sku: 'SP-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $spare['name'],
                type: 'spare_part',
                costPrice: $spare['cost'],
                price: $spare['price'],
                quantity: rand(5, 50),
                minStock: 3,
                maxStock: 75,
                users: $users
            );
        }

        // Sub-assemblies (10 parts)
        $subAssemblies = [
            ['name' => 'Motor Mount Assembly', 'cost' => 125.00, 'price' => 187.50],
            ['name' => 'Pump Housing Unit', 'cost' => 245.00, 'price' => 367.50],
            ['name' => 'Control Panel Door Kit', 'cost' => 85.00, 'price' => 127.50],
            ['name' => 'Gearbox Output Shaft Assy', 'cost' => 165.00, 'price' => 247.50],
            ['name' => 'Drive Mounting Bracket', 'cost' => 55.00, 'price' => 82.50],
            ['name' => 'Actuator Guide Rail', 'cost' => 95.00, 'price' => 142.50],
            ['name' => 'Sensor Mounting Plate', 'cost' => 32.00, 'price' => 48.00],
            ['name' => 'Controller DIN Rail Mount', 'cost' => 18.00, 'price' => 27.00],
            ['name' => 'Cylinder Mounting Block', 'cost' => 48.00, 'price' => 72.00],
            ['name' => 'Relay Base with Terminals', 'cost' => 28.00, 'price' => 42.00],
        ];

        foreach ($subAssemblies as $index => $assembly) {
            $parts[] = $this->createPartData(
                sku: 'SA-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $assembly['name'],
                type: 'sub_assembly',
                costPrice: $assembly['cost'],
                price: $assembly['price'],
                quantity: rand(5, 40),
                minStock: 5,
                maxStock: 60,
                isManufactured: true,
                users: $users
            );
        }

        $created = 0;

        foreach ($parts as $partData) {
            $part = Part::firstOrCreate(
                ['sku' => $partData['sku']],
                $partData
            );

            if ($part->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info("Created {$created} parts.");
    }

    /**
     * Create part data array.
     */
    private function createPartData(
        string $sku,
        string $name,
        string $type,
        float $costPrice,
        float $price,
        int $quantity,
        int $minStock,
        int $maxStock,
        $users,
        ?string $brand = null,
        ?string $material = null,
        string $unitOfMeasure = 'each',
        bool $isManufactured = false
    ): array {
        $reorderPoint = (int) ($minStock * 1.5);
        $reorderQuantity = (int) (($maxStock - $minStock) / 2);

        $status = match (true) {
            $quantity === 0 => 'out_of_stock',
            rand(0, 99) < 5 => 'discontinued',
            rand(0, 99) < 3 => 'pending',
            default => 'active',
        };

        return [
            'sku' => $sku,
            'part_number' => rand(0, 1) ? 'PN-' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT) : null,
            'barcode' => rand(0, 1) ? fake()->ean13() : null,
            'name' => $name,
            'description' => fake()->sentence(),
            'brand' => $brand,
            'manufacturer' => $brand ? ($brand . ' Manufacturing') : null,
            'type' => $type,
            'status' => $status,
            'unit_of_measure' => $unitOfMeasure,

            'height' => rand(0, 1) ? fake()->randomFloat(2, 1, 200) : null,
            'width' => rand(0, 1) ? fake()->randomFloat(2, 1, 200) : null,
            'length' => rand(0, 1) ? fake()->randomFloat(2, 1, 200) : null,
            'weight' => rand(0, 1) ? fake()->randomFloat(2, 0.1, 50) : null,
            'volume' => null,
            'colour' => rand(0, 1) ? fake()->safeColorName() : null,
            'material' => $material,

            'price' => $price,
            'cost_price' => $costPrice,
            'currency' => 'GBP',
            'tax_rate' => 20.00,
            'tax_code' => 'STD',
            'discount_percentage' => rand(0, 3) ? fake()->randomFloat(2, 5, 15) : null,

            'quantity' => $status === 'out_of_stock' ? 0 : $quantity,
            'min_stock_level' => $minStock,
            'max_stock_level' => $maxStock,
            'reorder_point' => $reorderPoint,
            'reorder_quantity' => $reorderQuantity,
            'lead_time_days' => rand(1, 30),
            'warehouse_location' => fake()->randomElement([
                'Warehouse A',
                'Warehouse B',
                'Distribution Centre',
                'Main Store',
            ]),
            'bin_location' => fake()->bothify('Shelf ?#-Bay ?#'),

            'is_active' => $status === 'active',
            'is_purchasable' => true,
            'is_sellable' => $type !== 'raw_material',
            'is_manufactured' => $isManufactured,
            'is_serialised' => $type === 'finished_good' && rand(0, 99) < 20,
            'is_batch_tracked' => $type === 'raw_material' && rand(0, 99) < 30,
            'is_real' => rand(0, 9) < 8,

            'meta' => json_encode([
                'supplier_code' => rand(0, 1) ? fake()->bothify('SUP-####') : null,
                'category' => fake()->randomElement([
                    'Electronics',
                    'Mechanical',
                    'Electrical',
                    'Hardware',
                    'Components',
                    'Consumables',
                ]),
                'subcategory' => rand(0, 1) ? fake()->word() : null,
                'notes' => rand(0, 2) ? fake()->sentence() : null,
                'warranty_months' => $type === 'finished_good' ? rand(12, 36) : null,
                'last_stock_check' => rand(0, 1) ? now()->subDays(rand(1, 90))->toDateTimeString() : null,
            ]),

            'created_by' => $users->random()->id,
            'updated_by' => rand(0, 3) ? $users->random()->id : null,
        ];
    }
}
