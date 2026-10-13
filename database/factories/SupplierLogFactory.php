<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierLog>
 */
class SupplierLogFactory extends Factory
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
            'supplier_id' => Supplier::factory(),
            'user_id' => User::factory(),
            'image_url' => 'suppliers/' . fake()->uuid() . '.jpg',
        ];
    }
}
