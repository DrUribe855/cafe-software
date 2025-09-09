<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Establishment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Establishment::factory()->create();

        $this->call(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Joaquin',
            'email' => 'test@example.com',
            'establishment_id' => 1,
            'document' => '123456789',
        ]);
        $admin->assignRole('admin');

        $employee = User::factory()->create([
            'name' => 'Paco',
            'email' => 'tester@example.com',
            'establishment_id' => 1,
            'document' => '987654321',
            'status' => 'Inactivo',
        ]);
        $employee->assignRole('employee');
    }
}
