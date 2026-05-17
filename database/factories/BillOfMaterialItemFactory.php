<?php

namespace Database\Factories;

use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialItem;
use App\Models\Part;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BillOfMaterialItem>
 */
class BillOfMaterialItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bill_of_material_id' => BillOfMaterial::inRandomOrder()->first()?->id ?? BillOfMaterial::factory(),
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'part_id' => Part::inRandomOrder()->first()?->id ?? Part::factory(),
            'quantity' => fake()->randomFloat(4, 0.0001, 100),
            'sequence' => fake()->optional(0.7)->numberBetween(1, 100),
            'notes' => fake()->optional(0.3)->sentence(),
            'is_optional' => fake()->boolean(20),
            'is_real' => true,
            'meta' => null,
            'created_by' => User::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Mark item as optional.
     */
    public function optional(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_optional' => true,
        ]);
    }

    /**
     * Mark item as required.
     */
    public function required(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_optional' => false,
        ]);
    }

    /**
     * Mark item as not real (test/demo data).
     */
    public function notReal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => false,
        ]);
    }

    /**
     * Set specific quantity.
     */
    public function withQuantity(float $quantity): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $quantity,
        ]);
    }

    /**
     * Set specific sequence number.
     */
    public function withSequence(int $sequence): static
    {
        return $this->state(fn (array $attributes) => [
            'sequence' => $sequence,
        ]);
    }

    /**
     * Set specific BOM.
     */
    public function forBOM(int|BillOfMaterial $bom): static
    {
        $bomId = $bom instanceof BillOfMaterial ? $bom->id : $bom;
        
        return $this->state(fn (array $attributes) => [
            'bill_of_material_id' => $bomId,
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
     * Set specific part.
     */
    public function forPart(int|Part $part): static
    {
        $partId = $part instanceof Part ? $part->id : $part;
        
        return $this->state(fn (array $attributes) => [
            'part_id' => $partId,
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
     * Add notes to the item.
     */
    public function withNotes(string $notes): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $notes,
        ]);
    }
}
