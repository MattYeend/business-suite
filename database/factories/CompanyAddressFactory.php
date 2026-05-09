<?php

namespace Database\Factories;
 
use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyAddress>
 */
class CompanyAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            CompanyAddress::TYPE_BILLING,
            CompanyAddress::TYPE_SHIPPING,
            CompanyAddress::TYPE_OFFICE,
            CompanyAddress::TYPE_WAREHOUSE,
        ];
 
        return [
            'company_id' => Company::factory(),
            'type' => fake()->randomElement($types),
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->optional(0.3)->secondaryAddress(),
            'city' => fake()->city(),
            'county' => fake('en_GB')->optional(0.7)->county(),
            'postal_code' => fake()->postcode(),
            'country' => 'United Kingdom',
            'is_primary' => false,
            'is_real' => false,
            'meta' => null,
            'created_by' => User::inRandomOrder()->first()?->id,
        ];
    }
 
    /**
     * Mark address as primary for the company.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }
 
    /**
     * Mark address as real (production data).
     */
    public function real(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => true,
        ]);
    }
 
    /**
     * Set address type to billing.
     */
    public function billing(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => CompanyAddress::TYPE_BILLING,
        ]);
    }
 
    /**
     * Set address type to shipping.
     */
    public function shipping(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => CompanyAddress::TYPE_SHIPPING,
        ]);
    }
 
    /**
     * Set address type to office.
     */
    public function office(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => CompanyAddress::TYPE_OFFICE,
        ]);
    }
 
    /**
     * Set address type to warehouse.
     */
    public function warehouse(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => CompanyAddress::TYPE_WAREHOUSE,
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
