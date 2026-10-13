<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ClosingLog;
use App\Models\Establishment;
use App\Models\LeaveRequest;
use App\Models\PastrieLog;
use App\Models\Product;
use App\Models\Refrigerator;
use App\Models\Supplier;
use App\Models\SupplierLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Smoke test for the testing infrastructure itself: migrations run on the
 * MySQL testing database, seeders produce the baseline data, and every
 * model factory creates a valid record with working relations.
 */
class DatabaseSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_baseline_data(): void
    {
        $this->seed();

        $this->assertSame(3, Role::count());
        $this->assertTrue(Role::where('name', 'super_admin')->exists());
        $this->assertTrue(Role::where('name', 'admin')->exists());
        $this->assertTrue(Role::where('name', 'employee')->exists());
        $this->assertSame(1, Establishment::count());
        $this->assertSame(3, User::count());
    }

    public function test_every_model_factory_creates_a_valid_record(): void
    {
        $establishment = Establishment::factory()->create();
        $user = User::factory()->create(['establishment_id' => $establishment->id]);

        $refrigerator = Refrigerator::factory()->create(['establishment_id' => $establishment->id]);
        $this->assertNotNull($refrigerator->establishment);

        $closingLog = ClosingLog::factory()->create([
            'establishment_id' => $establishment->id,
            'user_id' => $user->id,
            'refrigerator_id' => $refrigerator->id,
        ]);
        $this->assertNotNull($closingLog->user);
        $this->assertNotNull($closingLog->refrigerator);

        $pastrieLog = PastrieLog::factory()->create([
            'establishment_id' => $establishment->id,
            'user_id' => $user->id,
        ]);
        $this->assertNotNull($pastrieLog->user);

        $leaveRequest = LeaveRequest::factory()->create(['user_id' => $user->id]);
        $this->assertSame('Pendiente', $leaveRequest->status);
        $this->assertNotNull($leaveRequest->user);

        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $this->assertNotNull($product->category);

        $supplier = Supplier::factory()->create();
        $supplierLog = SupplierLog::factory()->create([
            'establishment_id' => $establishment->id,
            'supplier_id' => $supplier->id,
            'user_id' => $user->id,
        ]);
        $this->assertNotNull($supplierLog->supplier);
        $this->assertNotNull($supplierLog->user);
    }
}
