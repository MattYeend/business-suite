<?php

namespace Database\Factories;

use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pipeline>
 */
class PipelineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $entityTypes = ['deal', 'order', 'task', 'ticket', 'project', 'candidate', 'quote'];
        
        return [
            'name' => fake()->words(2, true) . ' Pipeline',
            'description' => fake()->optional(0.7)->sentence(),
            'entity_type' => fake()->randomElement($entityTypes),
            'is_default' => false,
            'is_active' => true,
            'position' => fake()->numberBetween(0, 10),
            'is_real' => true,
            'meta' => null,
            'created_by' => User::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Mark pipeline as default for its entity type.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    /**
     * Mark pipeline as inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Mark pipeline as not real (test/demo data).
     */
    public function notReal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => false,
        ]);
    }

    /**
     * Set specific entity type.
     */
    public function forEntityType(string $entityType): static
    {
        return $this->state(fn (array $attributes) => [
            'entity_type' => $entityType,
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
}
