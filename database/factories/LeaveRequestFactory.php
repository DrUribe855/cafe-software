<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('+1 week', '+2 months');
        $end = (clone $start)->modify('+' . fake()->numberBetween(1, 10) . ' days');

        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['Vacaciones', 'Permiso']),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'reason' => fake()->optional()->sentence(),
            'status' => 'Pendiente',
            'approved_by' => null,
            'approved_at' => null,
            'comments' => null,
        ];
    }

    /**
     * Indicate that the request was approved by the given approver.
     */
    public function approved(?int $approverId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Aprobado',
            'approved_by' => $approverId ?? User::factory(),
            'approved_at' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the request was rejected by the given approver.
     */
    public function rejected(?int $approverId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Rechazado',
            'approved_by' => $approverId ?? User::factory(),
            'approved_at' => now()->format('Y-m-d'),
            'comments' => fake()->sentence(),
        ]);
    }
}
