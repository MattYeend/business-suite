<?php

namespace Database\Factories;

use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PipelineStage>
 */
class PipelineStageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pipeline_id' => Pipeline::factory(),
            'name' => fake()->words(2, true),
            'color' => fake()->optional(0.8)->hexColor(),
            'position' => fake()->numberBetween(0, 10),
            'is_terminal' => false,
            'terminal_type' => null,
            'probability' => null,
            'sla_hours' => null,
            'requires_approval' => false,
            'is_real' => true,
            'meta' => null,
            'created_by' => User::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Mark stage as terminal with specific type.
     */
    public function terminal(string $terminalType = 'completed'): static
    {
        return $this->state(fn (array $attributes) => [
            'is_terminal' => true,
            'terminal_type' => $terminalType,
        ]);
    }

    /**
     * Mark stage as won (sales specific).
     */
    public function won(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_terminal' => true,
            'terminal_type' => 'won',
            'probability' => 100,
        ]);
    }

    /**
     * Mark stage as lost (sales specific).
     */
    public function lost(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_terminal' => true,
            'terminal_type' => 'lost',
            'probability' => 0,
        ]);
    }

    /**
     * Set win probability for sales stages.
     */
    public function withProbability(int $probability): static
    {
        return $this->state(fn (array $attributes) => [
            'probability' => $probability,
        ]);
    }

    /**
     * Set SLA hours for time-sensitive workflows.
     */
    public function withSla(int $hours): static
    {
        return $this->state(fn (array $attributes) => [
            'sla_hours' => $hours,
        ]);
    }

    /**
     * Mark stage as requiring approval.
     */
    public function requiresApproval(): static
    {
        return $this->state(fn (array $attributes) => [
            'requires_approval' => true,
        ]);
    }

    /**
     * Mark stage as not real (test/demo data).
     */
    public function notReal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => false,
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
