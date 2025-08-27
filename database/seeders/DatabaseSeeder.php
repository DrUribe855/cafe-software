<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Establishment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Establishment::factory()->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'establishment_id' => 1,
            'document' => '123456789',
        ]);
    }
}
