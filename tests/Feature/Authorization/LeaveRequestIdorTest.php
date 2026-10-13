<?php

namespace Tests\Feature\Authorization;

use App\Models\Establishment;
use App\Models\LeaveRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\WithRoleScopedUsers;
use Tests\TestCase;

class LeaveRequestIdorTest extends TestCase
{
    use RefreshDatabase;
    use WithRoleScopedUsers;

    public function test_employee_cannot_read_another_users_leave_requests(): void
    {
        $est = Establishment::factory()->create();
        $employeeA = $this->makeUserWithRole('employee', $est);
        $employeeB = $this->makeUserWithRole('employee', $est);

        LeaveRequest::factory()->create(['user_id' => $employeeA->id]);

        Sanctum::actingAs($employeeB);

        $this->getJson('/api/leave-requests/'.$employeeA->id.'?month='.now()->month.'&year='.now()->year)
            ->assertStatus(403);
    }

    public function test_admin_cannot_read_leave_requests_of_another_establishment(): void
    {
        $est1 = Establishment::factory()->create();
        $est2 = Establishment::factory()->create();
        $admin = $this->makeUserWithRole('admin', $est1);
        $otherEmployee = $this->makeUserWithRole('employee', $est2);

        LeaveRequest::factory()->create(['user_id' => $otherEmployee->id]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/establishments/'.$est2->id.'/leave-requests?month='.now()->month.'&year='.now()->year)
            ->assertStatus(403);
    }

    public function test_admin_cannot_approve_leave_request_of_another_establishment(): void
    {
        $est1 = Establishment::factory()->create();
        $est2 = Establishment::factory()->create();
        $admin = $this->makeUserWithRole('admin', $est1);
        $otherEmployee = $this->makeUserWithRole('employee', $est2);

        $leave = LeaveRequest::factory()->create(['user_id' => $otherEmployee->id]);

        Sanctum::actingAs($admin);

        $this->patchJson('/api/leave-requests/'.$leave->id, ['response' => 'Aprobado', 'comment' => null])
            ->assertStatus(403);
    }

    public function test_super_admin_can_read_and_approve_requests_of_any_establishment(): void
    {
        $est1 = Establishment::factory()->create();
        $est2 = Establishment::factory()->create();
        $superAdmin = $this->makeUserWithRole('super_admin', $est1);
        $otherEmployee = $this->makeUserWithRole('employee', $est2);

        $leave = LeaveRequest::factory()->create(['user_id' => $otherEmployee->id]);

        Sanctum::actingAs($superAdmin);

        $this->getJson('/api/establishments/'.$est2->id.'/leave-requests?month='.now()->month.'&year='.now()->year)
            ->assertStatus(200);

        $this->patchJson('/api/leave-requests/'.$leave->id, ['response' => 'Aprobado', 'comment' => null])
            ->assertStatus(200);
    }
}
