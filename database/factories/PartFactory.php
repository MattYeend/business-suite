<?php

namespace Database\Factories;

use App\Models\Part;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Part>
 */
class PartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            Part::TYPE_RAW_MATERIAL,
            Part::TYPE_FINISHED_GOOD,
            Part::TYPE_CONSUMABLE,
            Part::TYPE_SPARE_PART,
            Part::TYPE_SUB_ASSEMBLY,
        ];
        $statuses = [
            Part::STATUS_ACTIVE,
            Part::STATUS_DISCONTINUED,
            Part::STATUS_PENDING,
            Part::STATUS_OUT_OF_STOCK,
        ];

        $type = fake()->randomElement($types);

        $status = fake()->randomElement($statuses);

        $costPrice = fake()->randomFloat(2, 5, 500);
        $sellPrice = $costPrice * fake()->randomFloat(2, 1.2, 2.5); // 20-150% markup

        $quantity = fake()->numberBetween(0, 500);
        $minStock = fake()->numberBetween(5, 50);
        $maxStock = $minStock * fake()->numberBetween(3, 10);

        return [
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####-????')),
            'part_number' => fake()->optional(0.7)->bothify('PN-########'),
            'barcode' => fake()->optional(0.6)->ean13(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'brand' => fake()->optional(0.6)->company(),
            'manufacturer' => fake()->optional(0.7)->company(),
            'type' => $type,
            'status' => $status,
            'unit_of_measure' => fake()->randomElement(['each', 'kg', 'litre', 'metre', 'box', 'pallet']),

            'height' => fake()->optional(0.7)->randomFloat(2, 1, 200),
            'width' => fake()->optional(0.7)->randomFloat(2, 1, 200),
            'length' => fake()->optional(0.7)->randomFloat(2, 1, 200),
            'weight' => fake()->optional(0.7)->randomFloat(2, 0.1, 50),
            'volume' => fake()->optional(0.5)->randomFloat(2, 0.1, 100),
            'colour' => fake()->optional(0.5)->safeColorName(),
            'material' => fake()->optional(0.6)->randomElement([
                'Steel',
                'Aluminium',
                'Plastic',
                'Copper',
                'Brass',
                'Wood',
                'Composite',
                'Rubber',
            ]),

            'price' => $sellPrice,
            'cost_price' => $costPrice,
            'currency' => 'GBP',
            'tax_rate' => fake()->randomElement([0.00, 5.00, 20.00]),
            'tax_code' => fake()->optional(0.6)->randomElement(['STD', 'RED', 'ZER', 'EXM']),
            'discount_percentage' => fake()->optional(0.3)->randomFloat(2, 5, 25),

            'quantity' => $quantity,
            'min_stock_level' => $minStock,
            'max_stock_level' => $maxStock,
            'reorder_point' => fake()->optional(0.7)->numberBetween($minStock, $minStock * 2),
            'reorder_quantity' => fake()->optional(0.7)->numberBetween($minStock * 2, $maxStock),
            'lead_time_days' => fake()->optional(0.8)->numberBetween(1, 60),
            'warehouse_location' => fake()->optional(0.7)->randomElement([
                'Warehouse A',
                'Warehouse B',
                'Distribution Centre',
                'Main Store',
            ]),
            'bin_location' => fake()->optional(0.7)->bothify('Shelf ?#-Bay ?#'),

            'is_active' => $status === 'active',
            'is_purchasable' => fake()->boolean(85),
            'is_sellable' => fake()->boolean(90),
            'is_manufactured' => $type === 'sub_assembly' ? fake()->boolean(70) : fake()->boolean(20),
            'is_serialised' => fake()->boolean(15),
            'is_batch_tracked' => fake()->boolean(25),
            'is_real' => fake()->boolean(80),

            'meta' => [
                'supplier_code' => fake()->optional(0.6)->bothify('SUP-####'),
                'category' => fake()->optional(0.7)->randomElement([
                    'Electronics',
                    'Mechanical',
                    'Electrical',
                    'Hardware',
                    'Components',
                    'Consumables',
                ]),
                'subcategory' => fake()->optional(0.5)->word(),
                'notes' => fake()->optional(0.4)->sentence(),
                'warranty_months' => fake()->optional(0.5)->numberBetween(3, 36),
                'obsolete_replacement_sku' => null,
            ],

            'created_by' => User::inRandomOrder()->first()?->id,
            'updated_by' => null,
            'deleted_by' => null,
            'restored_by' => null,
            'restored_at' => null,
        ];
    }

    /**
     * Mark part as active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'is_active' => true,
        ]);
    }

    /**
     * Mark part as discontinued.
     */
    public function discontinued(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'discontinued',
            'is_active' => false,
        ]);
    }

    /**
     * Mark part as out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'out_of_stock',
            'quantity' => 0,
        ]);
    }

    /**
     * Mark part as not real (test/demo data).
     */
    public function notReal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => false,
        ]);
    }

    /**
     * Set part type.
     */
    public function type(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Mark part as serialised.
     */
    public function serialised(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_serialised' => true,
        ]);
    }

    /**
     * Mark part as batch tracked.
     */
    public function batchTracked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_batch_tracked' => true,
        ]);
    }

    /**
     * Mark part as manufactured.
     */
    public function manufactured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_manufactured' => true,
        ]);
    }

    /**
     * Set stock levels.
     */
    public function withStock(int $quantity, int $minStock, ?int $maxStock = null): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $quantity,
            'min_stock_level' => $minStock,
            'max_stock_level' => $maxStock ?? $minStock * 5,
        ]);
    }

    /**
     * Add custom metadata.
     */
    public function withMeta(array $meta): static
    {
        return $this->state(fn (array $attributes) => [
            'meta' => array_merge($attributes['meta'] ?? [], $meta),
        ]);
    }

    /**
     * Set pricing.
     */
    public function withPricing(float $costPrice, float $sellPrice): static
    {
        return $this->state(fn (array $attributes) => [
            'cost_price' => $costPrice,
            'price' => $sellPrice,
        ]);
    }
}
