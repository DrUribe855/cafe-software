<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Contract tests for S2.3: GET /api/user must expose the authenticated
 * user's roles inside the `user` object (the frontend userStore reads
 * data.user.roles) while preserving the existing top-level fields
 * (the router guard reads data.roles).
 */
class ApiUserContractTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUserWithRole(string $roleName, Establishment $establishment): User
    {
        Role::findOrCreate($roleName);

        $user = User::factory()->create(['establishment_id' => $establishment->id]);
        $user->assignRole($roleName);

        Sanctum::actingAs($user);

        return $user;
    }

    public function test_get_api_user_returns_roles_inside_the_user_object(): void
    {
        $establishment = Establishment::factory()->create();
        $user = $this->actingAsUserWithRole('employee', $establishment);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonStructure([
                'user' => ['id', 'roles' => [['id', 'name']]],
            ]);

        $this->assertSame(
            ['employee'],
            collect($response->json('user.roles'))->pluck('name')->all()
        );
    }

    public function test_get_api_user_preserves_existing_top_level_fields(): void
    {
        $establishment = Establishment::factory()->create();
        $user = $this->actingAsUserWithRole('admin', $establishment);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonPath('roles.0.name', 'admin')
            ->assertJsonPath('establishment_id', $user->establishment_id)
            ->assertJsonPath('establishment_name', $establishment->name);
    }
}
