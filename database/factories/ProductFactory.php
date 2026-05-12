<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            Product::STATUS_ACTIVE,
            Product::STATUS_DISCONTINUED,
            Product::STATUS_PENDING,
            Product::STATUS_OUT_OF_STOCK
        ];
        $status = fake()->randomElement($statuses);

        $price = fake()->randomFloat(2, 10, 1000);
        $quantity = fake()->numberBetween(0, 500);
        $minStock = fake()->numberBetween(5, 50);
        $maxStock = $minStock * fake()->numberBetween(3, 10);

        return [
            'sku' => strtoupper(fake()->unique()->bothify('PRD-####-????')),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => $price,
            'currency' => 'GBP',
            'status' => $status,

            'quantity' => $quantity,
            'min_stock_level' => $minStock,
            'max_stock_level' => $maxStock,
            'reorder_point' => fake()->optional(0.7)->numberBetween($minStock, $minStock * 2),
            'reorder_quantity' => fake()->optional(0.7)->numberBetween($minStock * 2, $maxStock),
            'lead_time_days' => fake()->optional(0.8)->numberBetween(1, 60),

            'is_real' => fake()->boolean(80),

            'meta' => [
                'category' => fake()->optional(0.7)->randomElement([
                    'Electronics',
                    'Mechanical',
                    'Electrical',
                    'Hardware',
                    'Software',
                    'Services',
                ]),
                'subcategory' => fake()->optional(0.5)->word(),
                'notes' => fake()->optional(0.4)->sentence(),
                'warranty_months' => fake()->optional(0.5)->numberBetween(3, 36),
            ],

            'created_by' => User::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Mark product as active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Mark product as discontinued.
     */
    public function discontinued(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'discontinued',
        ]);
    }

    /**
     * Mark product as out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'out_of_stock',
            'quantity' => 0,
        ]);
    }

    /**
     * Mark product as not real (test/demo data).
     */
    public function notReal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => false,
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
    public function withPricing(float $price, string $currency = 'GBP'): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => $price,
            'currency' => $currency,
        ]);
    }
}
