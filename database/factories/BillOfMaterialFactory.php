<?php

namespace Database\Factories;

use App\Models\BillOfMaterial;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BillOfMaterial>
 */
class BillOfMaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prefix = fake()->randomElement(['BOM', 'BILL', 'MAT']);
        $number = fake()->unique()->numberBetween(10000, 99999);
        
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'bom_number' => "{$prefix}-{$number}",
            'version' => '1.0',
            'description' => fake()->optional(0.7)->sentence(),
            'is_active' => true,
            'effective_from' => now()->subDays(fake()->numberBetween(0, 30)),
            'effective_to' => null,
            'is_real' => true,
            'meta' => null,
            'created_by' => User::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Mark BOM as inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'effective_to' => now()->subDays(fake()->numberBetween(1, 10)),
        ]);
    }

    /**
     * Mark BOM as not real (test/demo data).
     */
    public function notReal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => false,
        ]);
    }

    /**
     * Set specific version number.
     */
    public function version(string $version): static
    {
        return $this->state(fn (array $attributes) => [
            'version' => $version,
        ]);
    }

    /**
     * Set effective date range.
     */
    public function effectiveBetween(\DateTimeInterface $from, ?\DateTimeInterface $to = null): static
    {
        return $this->state(fn (array $attributes) => [
            'effective_from' => $from,
            'effective_to' => $to,
        ]);
    }

    /**
     * Mark BOM as expired (has effective_to date in the past).
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'effective_to' => now()->subDays(fake()->numberBetween(1, 30)),
            'is_active' => false,
        ]);
    }

    /**
     * Set specific product.
     */
    public function forProduct(int|Product $product): static
    {
        $productId = $product instanceof Product ? $product->id : $product;
        
        return $this->state(fn (array $attributes) => [
            'product_id' => $productId,
        ]);
    }

    /**
     * Add custom metadata.
     */
    public function withMeta(array $meta): static
    {
        return $this->state(fn (array $attributes) => [
            'meta' => $meta,
        ]);
    }

    /**
     * Set custom BOM number prefix.
     */
    public function withPrefix(string $prefix): static
    {
        $number = fake()->unique()->numberBetween(10000, 99999);
        
        return $this->state(fn (array $attributes) => [
            'bom_number' => "{$prefix}-{$number}",
        ]);
    }
}
