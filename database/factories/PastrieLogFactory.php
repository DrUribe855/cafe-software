<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PastrieLog>
 */
class PastrieLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'establishment_id' => Establishment::factory(),
            'user_id' => User::factory(),
            'schedule' => fake()->randomElement(['apertura', 'cambio de turno', 'cierre']),
            'image_url' => 'pastrie/' . fake()->uuid() . '.jpg',
        ];
    }
}
