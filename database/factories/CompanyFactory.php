<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyIndustry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'industry_id' => CompanyIndustry::factory(),
            'email' => fake()->companyEmail(),
            'website' => fake()->domainName(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'region' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'employee_count' => fake()->randomElement([
                fake()->numberBetween(1, 9),
                fake()->numberBetween(10, 49),
                fake()->numberBetween(50, 249),
                fake()->numberBetween(250, 999),
                fake()->numberBetween(1000, 10000),
            ]),
            'annual_revenue' => fake()->randomFloat(2, 100000, 50000000),
            'is_real' => true,
            'meta' => fake()->optional(0.3)->passthrough([
                'linkedin' => 'https://linkedin.com/company/' . fake()->slug(),
                'twitter' => '@' . fake()->userName(),
                'founded_year' => fake()->year(),
            ]),
            'created_by' => User::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Indicate that the company is a test company.
     */
    public function test(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => false,
            'name' => 'TEST - ' . $attributes['name'],
        ]);
    }

    /**
     * Create a micro company (1-9 employees).
     */
    public function micro(): static
    {
        return $this->state(fn (array $attributes) => [
            'employee_count' => fake()->numberBetween(1, 9),
            'annual_revenue' => fake()->randomFloat(2, 50000, 500000),
        ]);
    }

    /**
     * Create a small company (10-49 employees).
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'employee_count' => fake()->numberBetween(10, 49),
            'annual_revenue' => fake()->randomFloat(2, 500000, 5000000),
        ]);
    }

    /**
     * Create a medium company (50-249 employees).
     */
    public function medium(): static
    {
        return $this->state(fn (array $attributes) => [
            'employee_count' => fake()->numberBetween(50, 249),
            'annual_revenue' => fake()->randomFloat(2, 5000000, 50000000),
        ]);
    }

    /**
     * Create a large company (250-999 employees).
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'employee_count' => fake()->numberBetween(250, 999),
            'annual_revenue' => fake()->randomFloat(2, 50000000, 500000000),
        ]);
    }

    /**
     * Create an enterprise company (1000+ employees).
     */
    public function enterprise(): static
    {
        return $this->state(fn (array $attributes) => [
            'employee_count' => fake()->numberBetween(1000, 50000),
            'annual_revenue' => fake()->randomFloat(2, 500000000, 10000000000),
        ]);
    }

    /**
     * Create a company without optional fields.
     */
    public function minimal(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => null,
            'website' => null,
            'phone' => null,
            'address' => null,
            'city' => null,
            'region' => null,
            'postal_code' => null,
            'country' => null,
            'employee_count' => null,
            'annual_revenue' => null,
            'meta' => null,
        ]);
    }

    /**
     * Create a company in a specific country.
     */
    public function inCountry(string $country): static
    {
        return $this->state(fn (array $attributes) => [
            'country' => $country,
        ]);
    }

    /**
     * Create a company with specific industry.
     */
    public function withIndustry(int $industryId): static
    {
        return $this->state(fn (array $attributes) => [
            'industry_id' => $industryId,
        ]);
    }
}
