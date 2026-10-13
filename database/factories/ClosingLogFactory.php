<?php

namespace Database\Factories;

use App\Models\Refrigerator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClosingLog>
 */
class ClosingLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'establishment_id' => fn () => User::factory()->create()->establishment_id,
            'user_id' => User::factory(),
            'refrigerator_id' => Refrigerator::factory(),
            'schedule' => fake()->optional()->word(),
            'image_url' => 'closing/' . fake()->uuid() . '.jpg',
            'temperature' => fake()->randomFloat(2, -5, 15),
        ];
    }
}
