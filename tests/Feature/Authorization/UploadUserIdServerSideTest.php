<?php

namespace Tests\Feature\Authorization;

use App\Models\Establishment;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\WithRoleScopedUsers;
use Tests\TestCase;

class UploadUserIdServerSideTest extends TestCase
{
    use RefreshDatabase;
    use WithRoleScopedUsers;

    public function test_pastrie_upload_uses_authenticated_user_id_not_client_supplied(): void
    {
        $est = Establishment::factory()->create();
        $uploader = $this->makeUserWithRole('employee', $est);
        $victim = $this->makeUserWithRole('employee', $est);

        Sanctum::actingAs($uploader);

        $this->post('/api/upload-image', [
            'establishment_id' => $est->id,
            'user_id' => $victim->id,
            'schedule' => 'apertura',
            'images' => [UploadedFile::fake()->image('photo.jpg')],
        ])->assertStatus(201);

        $this->assertDatabaseHas('pastrie_logs', [
            'user_id' => $uploader->id,
        ]);
        $this->assertDatabaseMissing('pastrie_logs', [
            'user_id' => $victim->id,
        ]);
    }

    public function test_supplier_upload_uses_authenticated_user_id_not_client_supplied(): void
    {
        $est = Establishment::factory()->create();
        $uploader = $this->makeUserWithRole('employee', $est);
        $victim = $this->makeUserWithRole('employee', $est);
        $supplier = Supplier::factory()->create();

        Sanctum::actingAs($uploader);

        $this->post('/api/suppliers/'.$supplier->id.'/upload-images', [
            'establishment_id' => $est->id,
            'user_id' => $victim->id,
            'images' => [UploadedFile::fake()->image('photo.jpg')],
        ])->assertStatus(201);

        $this->assertDatabaseHas('supplier_logs', [
            'user_id' => $uploader->id,
        ]);
        $this->assertDatabaseMissing('supplier_logs', [
            'user_id' => $victim->id,
        ]);
    }

    public function test_admin_cannot_upload_to_another_establishment(): void
    {
        $est1 = Establishment::factory()->create();
        $est2 = Establishment::factory()->create();
        $admin = $this->makeUserWithRole('admin', $est1);

        Sanctum::actingAs($admin);

        $this->post('/api/upload-image', [
            'establishment_id' => $est2->id,
            'user_id' => $admin->id,
            'schedule' => 'apertura',
            'images' => [UploadedFile::fake()->image('photo.jpg')],
        ])->assertStatus(403);
    }
}
