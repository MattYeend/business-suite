<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Product::exists()) {
            $this->command->info('Products already seeded, skipping...');
            return;
        }
        
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $products = [];

        // Software Products (15 products)
        $softwareProducts = [
            ['name' => 'CRM Software License', 'price' => 499.00],
            ['name' => 'Project Management Tool', 'price' => 299.00],
            ['name' => 'Accounting Software Annual', 'price' => 599.00],
            ['name' => 'CAD Design Suite', 'price' => 1299.00],
            ['name' => 'Inventory Management System', 'price' => 799.00],
            ['name' => 'Email Marketing Platform', 'price' => 199.00],
            ['name' => 'HR Management System', 'price' => 899.00],
            ['name' => 'Analytics Dashboard Pro', 'price' => 399.00],
            ['name' => 'Document Management System', 'price' => 449.00],
            ['name' => 'Time Tracking Software', 'price' => 149.00],
            ['name' => 'Customer Support Platform', 'price' => 699.00],
            ['name' => 'E-commerce Platform License', 'price' => 999.00],
            ['name' => 'Backup & Recovery Solution', 'price' => 349.00],
            ['name' => 'Team Collaboration Tool', 'price' => 249.00],
            ['name' => 'Security Suite Enterprise', 'price' => 1499.00],
        ];

        foreach ($softwareProducts as $index => $product) {
            $products[] = $this->createProductData(
                sku: 'SOFT-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $product['name'],
                price: $product['price'],
                quantity: rand(100, 1000),
                minStock: 50,
                maxStock: 1500,
                users: $users,
                category: 'Software'
            );
        }

        // Hardware Products (20 products)
        $hardwareProducts = [
            ['name' => 'Desktop Computer Workstation', 'price' => 1299.00],
            ['name' => 'Laptop Business Model', 'price' => 899.00],
            ['name' => 'Network Router Enterprise', 'price' => 349.00],
            ['name' => 'Server Rack 42U', 'price' => 2499.00],
            ['name' => 'Laser Printer Colour', 'price' => 549.00],
            ['name' => 'Scanner Document Feeder', 'price' => 299.00],
            ['name' => 'Monitor 27" 4K', 'price' => 449.00],
            ['name' => 'Keyboard Mechanical RGB', 'price' => 129.00],
            ['name' => 'Mouse Wireless Ergonomic', 'price' => 59.00],
            ['name' => 'Webcam HD 1080p', 'price' => 89.00],
            ['name' => 'Headset Noise Cancelling', 'price' => 199.00],
            ['name' => 'External Hard Drive 4TB', 'price' => 149.00],
            ['name' => 'USB Hub 10-Port', 'price' => 45.00],
            ['name' => 'Docking Station Universal', 'price' => 249.00],
            ['name' => 'KVM Switch 4-Port', 'price' => 89.00],
            ['name' => 'UPS Battery Backup 1500VA', 'price' => 299.00],
            ['name' => 'Network Switch 24-Port', 'price' => 449.00],
            ['name' => 'Wireless Access Point', 'price' => 199.00],
            ['name' => 'Cable Management Kit', 'price' => 39.00],
            ['name' => 'Surge Protector 12-Outlet', 'price' => 49.00],
        ];

        foreach ($hardwareProducts as $index => $product) {
            $products[] = $this->createProductData(
                sku: 'HARD-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $product['name'],
                price: $product['price'],
                quantity: rand(20, 200),
                minStock: 10,
                maxStock: 300,
                users: $users,
                category: 'Hardware'
            );
        }

        // Service Products (15 products)
        $serviceProducts = [
            ['name' => 'IT Support Contract Annual', 'price' => 2499.00],
            ['name' => 'Cloud Hosting Monthly', 'price' => 199.00],
            ['name' => 'Website Maintenance Package', 'price' => 299.00],
            ['name' => 'SEO Optimization Service', 'price' => 799.00],
            ['name' => 'Data Backup Service', 'price' => 149.00],
            ['name' => 'Network Security Audit', 'price' => 1299.00],
            ['name' => 'Software Development Hours', 'price' => 125.00],
            ['name' => 'Consulting Day Rate', 'price' => 850.00],
            ['name' => 'Training Session Per Person', 'price' => 199.00],
            ['name' => 'System Integration Service', 'price' => 2999.00],
            ['name' => 'Migration Service Package', 'price' => 1499.00],
            ['name' => 'Technical Support Tier 2', 'price' => 399.00],
            ['name' => 'Penetration Testing', 'price' => 2499.00],
            ['name' => 'Database Optimization', 'price' => 699.00],
            ['name' => 'Disaster Recovery Planning', 'price' => 1899.00],
        ];

        foreach ($serviceProducts as $index => $product) {
            $products[] = $this->createProductData(
                sku: 'SERV-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $product['name'],
                price: $product['price'],
                quantity: rand(50, 500),
                minStock: 20,
                maxStock: 750,
                users: $users,
                category: 'Services'
            );
        }

        // Office Supplies (15 products)
        $officeSupplies = [
            ['name' => 'A4 Paper Ream 500 Sheets', 'price' => 4.99],
            ['name' => 'Pen Ballpoint Black Box/50', 'price' => 12.99],
            ['name' => 'Stapler Heavy Duty', 'price' => 18.99],
            ['name' => 'Hole Punch 2-Hole', 'price' => 9.99],
            ['name' => 'Folders Ring Binder Pack/10', 'price' => 24.99],
            ['name' => 'Sticky Notes 3x3 Pack/12', 'price' => 8.99],
            ['name' => 'Marker Whiteboard Set/4', 'price' => 6.99],
            ['name' => 'Scissors Office Grade', 'price' => 5.99],
            ['name' => 'Calculator Desktop', 'price' => 19.99],
            ['name' => 'Desk Organiser Set', 'price' => 29.99],
            ['name' => 'Label Maker Portable', 'price' => 39.99],
            ['name' => 'Paper Clips Box/1000', 'price' => 3.99],
            ['name' => 'Rubber Bands Assorted', 'price' => 2.99],
            ['name' => 'Correction Tape Pack/3', 'price' => 7.99],
            ['name' => 'Filing Cabinet 4-Drawer', 'price' => 299.99],
        ];

        foreach ($officeSupplies as $index => $product) {
            $products[] = $this->createProductData(
                sku: 'OFF-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $product['name'],
                price: $product['price'],
                quantity: rand(100, 1000),
                minStock: 50,
                maxStock: 1500,
                users: $users,
                category: 'Office Supplies'
            );
        }

        // Electronics (15 products)
        $electronics = [
            ['name' => 'Tablet 10" 128GB', 'price' => 399.00],
            ['name' => 'Smartphone Business Edition', 'price' => 699.00],
            ['name' => 'Smart TV 55" 4K', 'price' => 799.00],
            ['name' => 'Projector Full HD', 'price' => 549.00],
            ['name' => 'Bluetooth Speaker Portable', 'price' => 89.00],
            ['name' => 'Earbuds Wireless Pro', 'price' => 179.00],
            ['name' => 'Smartwatch Fitness Tracker', 'price' => 249.00],
            ['name' => 'Digital Camera 24MP', 'price' => 599.00],
            ['name' => 'Action Camera 4K', 'price' => 299.00],
            ['name' => 'Dash Cam Dual Lens', 'price' => 129.00],
            ['name' => 'E-Reader 6" Display', 'price' => 119.00],
            ['name' => 'Graphic Tablet Drawing', 'price' => 349.00],
            ['name' => 'Microphone USB Condenser', 'price' => 99.00],
            ['name' => 'Ring Light LED 18"', 'price' => 79.00],
            ['name' => 'Power Bank 20000mAh', 'price' => 49.00],
        ];

        foreach ($electronics as $index => $product) {
            $products[] = $this->createProductData(
                sku: 'ELEC-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                name: $product['name'],
                price: $product['price'],
                quantity: rand(30, 300),
                minStock: 15,
                maxStock: 450,
                users: $users,
                category: 'Electronics'
            );
        }

        $created = 0;

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );

            if ($product->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info("Created {$created} products.");
    }

    /**
     * Create product data array.
     */
    private function createProductData(
        string $sku,
        string $name,
        float $price,
        int $quantity,
        int $minStock,
        int $maxStock,
        $users,
        ?string $category = null
    ): array {
        $reorderPoint = (int) ($minStock * 1.5);
        $reorderQuantity = (int) (($maxStock - $minStock) / 2);

        $status = match (true) {
            $quantity === 0 => 'out_of_stock',
            rand(0, 99) < 5 => 'discontinued',
            rand(0, 99) < 3 => 'pending',
            default => 'active',
        };

        $descriptions = [
            'Premium quality product for professional use.',
            'Industry-standard solution for business needs.',
            'Reliable and efficient performance guaranteed.',
            'Essential tool for modern workplaces.',
            'High-quality construction and materials.',
            'Designed for productivity and efficiency.',
            'Professional-grade equipment and service.',
            'Trusted by businesses worldwide.',
        ];

        return [
            'sku' => $sku,
            'name' => $name,
            'description' => $descriptions[array_rand($descriptions)],
            'price' => $price,
            'currency' => 'GBP',
            'status' => $status,

            'quantity' => $status === 'out_of_stock' ? 0 : $quantity,
            'min_stock_level' => $minStock,
            'max_stock_level' => $maxStock,
            'reorder_point' => $reorderPoint,
            'reorder_quantity' => $reorderQuantity,
            'lead_time_days' => rand(1, 30),

            'is_real' => rand(0, 9) < 8,

            'meta' => json_encode([
                'category' => $category,
                'subcategory' => rand(0, 1) ? ['Professional', 'Enterprise', 'Standard', 'Premium'][array_rand(['Professional', 'Enterprise', 'Standard', 'Premium'])] : null,
                'warranty_months' => rand(0, 1) ? rand(12, 36) : null,
                'notes' => rand(0, 2) ? ['Stock item', 'Fast shipping available', 'Volume discounts available'][array_rand(['Stock item', 'Fast shipping available', 'Volume discounts available'])] : null,
            ]),

            'created_by' => $users->random()->id,
            'updated_by' => rand(0, 3) ? $users->random()->id : null,
        ];
    }
}
