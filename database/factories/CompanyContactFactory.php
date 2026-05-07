<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyContact>
 */
class CompanyContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional(0.7)->phoneNumber(),
            'mobile' => fake()->optional(0.8)->phoneNumber(),
            'job_title' => fake()->optional(0.85)->jobTitle(),
            'is_primary' => false,
            'created_by' => User::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Mark contact as primary for their company.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }

    /**
     * Mark contact as not real (test/demo data).
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
