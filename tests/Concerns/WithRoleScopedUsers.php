<?php

namespace Tests\Concerns;

use App\Models\Establishment;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

trait WithRoleScopedUsers
{
    protected function seedRoles(): void
    {
        foreach (['super_admin', 'admin', 'employee'] as $role) {
            Role::findOrCreate($role);
        }
    }

    protected function makeUserWithRole(string $role, ?Establishment $establishment = null): User
    {
        $this->seedRoles();

        $user = User::factory()->create([
            'establishment_id' => ($establishment ?? Establishment::factory()->create())->id,
        ]);
        $user->assignRole($role);

        return $user;
    }

    protected function actingAsRole(string $role, ?Establishment $establishment = null): User
    {
        $user = $this->makeUserWithRole($role, $establishment);
        Sanctum::actingAs($user);

        return $user;
    }
}
