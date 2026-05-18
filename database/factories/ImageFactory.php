<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        $mimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf', // Technical drawings
        ];

        $mimeType = fake()->randomElement($mimeTypes);
        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
            default => 'jpg',
        };

        $fileName = fake()->uuid() . '.' . $extension;
        $width = in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ? fake()->numberBetween(800, 3000)
            : null;
        $height = $width ? (int)($width / fake()->randomFloat(2, 1.2, 1.8)) : null;

        return [
            'file_name' => $fileName,
            'file_path' => 'images/' . date('Y/m') . '/' . $fileName,
            'disk' => 'public',
            'mime_type' => $mimeType,
            'file_size' => fake()->numberBetween(50000, 5000000), // 50KB to 5MB
            'width' => $width,
            'height' => $height,
            'alt_text' => fake()->optional(0.7)->sentence(6),
            'title' => fake()->optional(0.6)->words(3, true),
            'description' => fake()->optional(0.5)->sentence(),
            'uploaded_by' => User::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Mark as technical drawing/PDF.
     */
    public function technicalDrawing(): static
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => 'application/pdf',
            'file_name' => fake()->uuid() . '.pdf',
            'file_path' => 'drawings/' . date('Y/m') . '/' . fake()->uuid() . '.pdf',
            'width' => null,
            'height' => null,
            'alt_text' => 'Technical drawing',
        ]);
    }

    /**
     * Mark as product image.
     */
    public function productImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => fake()->randomElement(['image/jpeg', 'image/png']),
            'width' => fake()->numberBetween(1200, 2400),
            'height' => fake()->numberBetween(1200, 2400),
            'alt_text' => 'Product image',
        ]);
    }

    /**
     * Set specific dimensions.
     */
    public function withDimensions(int $width, int $height): static
    {
        return $this->state(fn (array $attributes) => [
            'width' => $width,
            'height' => $height,
        ]);
    }

    /**
     * Set file size.
     */
    public function withFileSize(int $bytes): static
    {
        return $this->state(fn (array $attributes) => [
            'file_size' => $bytes,
        ]);
    }
}
