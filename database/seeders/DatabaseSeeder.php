<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Establishment;
use App\Models\Refrigerator;
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
        Establishment::factory()->create();

        $this->call(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Joaquin',
            'email' => 'test@example.com',
            'establishment_id' => 1,
            'document' => '123456789',
            'status' => 'Activo',
            "password" => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $employee = User::factory()->create([
            'name' => 'Paco',
            'email' => 'tester@example.com',
            'establishment_id' => 1,
            'document' => '987654321',
            'status' => 'Inactivo',
            "password" => bcrypt('password'),
        ]);
        $employee->assignRole('employee');

       /* ...  $fridges = [
            'Nevera 1',
            'Nevera 2',
            'Nevera 3',
            'Nevera 4',
            'Nevera 5',
            'Nevera 6',
            'Nevera 7',
        ]; */

        /* ... $id = 1;
        foreach ($fridges as $name) {
            Refrigerator::create([
                'id' => $id,
                'name' => $name,
                'establishment_id' => 1,
            ]);
            $id++;
        } */
    }
}
