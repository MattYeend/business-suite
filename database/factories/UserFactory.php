<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_user' => true,
            'is_admin' => false,
            'is_super_admin' => false,
            'phone' => fake()->phoneNumber(),
            'is_real' => false,
            'meta' => json_encode([
                'department' => fake()->randomElement(['Sales', 'Marketing', 'Support', 'Engineering', 'HR']),
                'office_location' => fake()->randomElement(['Head Office', 'Regional Office', 'Remote']),
            ]),
            'created_by' => null,
            'updated_by' => null,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_user' => false,
            'is_admin' => true,
            'is_super_admin' => false,
        ]);
    }

    /**
     * Indicate that the user is a super admin.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_user' => false,
            'is_admin' => false,
            'is_super_admin' => true,
        ]);
    }

    /**
     * Indicate that the user is a test user.
     */
    public function testUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_real' => false,
        ]);
    }
}
