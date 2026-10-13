<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(2, true),
            'description' => fake()->optional()->sentence(),
            'price' => fake()->randomFloat(2, 1, 50),
            'tag' => fake()->optional()->word(),
            'custom_tags' => null,
            'image_url' => null,
            'recommended' => false,
        ];
    }

    /**
     * Indicate that the product is recommended.
     */
    public function recommended(): static
    {
        return $this->state(fn (array $attributes) => [
            'recommended' => true,
        ]);
    }
}
