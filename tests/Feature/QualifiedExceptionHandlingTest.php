<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Regression tests for S2.2: the unqualified catch(Exception) blocks inside
 * namespaced controllers resolved to non-existent namespaced exception
 * classes (e.g. App\Http\Controllers\User\Exception), so runtime exceptions
 * escaped to the generic 500 handler instead of the controller's own error
 * response. Qualifying them as \Exception restores the intended contract.
 *
 * Covered paths:
 * - UserController::createUser (assignRole with an unknown role throws)
 * - UserController::editUser (syncRoles with an unknown role throws)
 * - LeaveRequestController::saveRequestResponse (invalid enum status throws
 *   a QueryException under MySQL strict mode)
 *
 * Not covered by a runnable RED (verified structurally instead):
 * - UserController::deleteUser and LeaveRequestController::getRequestSum
 *   have no cheap deterministic trigger; their catch blocks received the
 *   identical \Exception qualification.
 */
class QualifiedExceptionHandlingTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        Role::findOrCreate('admin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        return $admin;
    }

    public function test_create_user_returns_controller_error_response_when_role_assignment_fails(): void
    {
        $this->actingAsAdmin();
        $establishment = Establishment::factory()->create();

        $response = $this->postJson('/api/users', [
            'document' => '12345678',
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
            'role' => 'rol_inexistente',
            'status' => 'Activo',
            'password' => 'secret123',
            'establishmentId' => $establishment->id,
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Error al crear usuario',
                'status' => false,
            ]);

        $this->assertNotEmpty($response->json('error'));
    }

    public function test_edit_user_returns_controller_error_response_when_role_sync_fails(): void
    {
        $this->actingAsAdmin();
        $target = User::factory()->create();

        $response = $this->putJson("/api/users/{$target->id}", [
            'document' => $target->document,
            'name' => 'Nombre Modificado',
            'email' => $target->email,
            'role' => 'rol_inexistente',
            'status' => 'Activo',
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Error al modificar usuario',
                'status' => false,
            ]);

        $this->assertNotEmpty($response->json('error'));

        /* The exception happens before save(), so nothing is persisted */
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => $target->name,
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $target->id,
            'name' => 'Nombre Modificado',
        ]);
    }

    public function test_save_request_response_returns_controller_error_response_on_invalid_status(): void
    {
        $this->actingAsAdmin();
        $leave = LeaveRequest::factory()->create();

        $response = $this->patchJson("/api/leave-requests/{$leave->id}", [
            'response' => 'EstadoInvalido',
            'comment' => null,
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Error al registrar respuesta de solicitud',
            ]);

        $this->assertNotEmpty($response->json('error'));

        /* The invalid status never reaches the database */
        $this->assertDatabaseHas('leave_requests', [
            'id' => $leave->id,
            'status' => 'Pendiente',
        ]);
    }
}
