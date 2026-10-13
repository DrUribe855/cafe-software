<?php

namespace Tests\Feature\Authorization;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\WithRoleScopedUsers;
use Tests\TestCase;

class RoleHierarchyTest extends TestCase
{
    use RefreshDatabase;
    use WithRoleScopedUsers;

    public function test_super_admin_can_fetch_users_of_any_establishment(): void
    {
        $est1 = Establishment::factory()->create();
        $est2 = Establishment::factory()->create();
        User::factory()->create(['establishment_id' => $est1->id]);
        User::factory()->create(['establishment_id' => $est2->id]);

        $this->actingAsRole('super_admin', $est1);

        $this->getJson('/api/users?establishmentId='.$est1->id)->assertStatus(200);
        $this->getJson('/api/users?establishmentId='.$est2->id)->assertStatus(200);
    }

    public function test_admin_can_fetch_users_of_own_establishment_only(): void
    {
        $est1 = Establishment::factory()->create();
        $est2 = Establishment::factory()->create();
        User::factory()->create(['establishment_id' => $est1->id]);
        User::factory()->create(['establishment_id' => $est2->id]);

        $this->actingAsRole('admin', $est1);

        $this->getJson('/api/users?establishmentId='.$est1->id)->assertStatus(200);
        $this->getJson('/api/users?establishmentId='.$est2->id)->assertStatus(403);
    }

    public function test_employee_cannot_access_users_endpoint(): void
    {
        $est1 = Establishment::factory()->create();
        User::factory()->create(['establishment_id' => $est1->id]);

        $this->actingAsRole('employee', $est1);

        $this->getJson('/api/users?establishmentId='.$est1->id)->assertStatus(403);
    }

    public function test_admin_cannot_list_closing_logs_of_another_establishment(): void
    {
        $est1 = Establishment::factory()->create();
        $est2 = Establishment::factory()->create();

        $this->actingAsRole('admin', $est1);

        $this->getJson('/api/closing-logs?establishment_id='.$est2->id.'&date='.now()->toDateString())
            ->assertStatus(403);
    }
}
