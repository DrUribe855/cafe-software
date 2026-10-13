<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\PastrieLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test for S2.1: User::pastrieLogs() referenced a non-existent
 * PastrieLogs::class, so any access to the relation was a fatal error.
 * The relation must resolve to the real PastrieLog model.
 */
class UserPastrieLogsRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_pastrie_logs_relation_resolves_and_returns_the_logs(): void
    {
        $establishment = Establishment::factory()->create();
        $user = User::factory()->create(['establishment_id' => $establishment->id]);

        $log = PastrieLog::factory()->create([
            'establishment_id' => $establishment->id,
            'user_id' => $user->id,
        ]);

        $this->assertCount(1, $user->pastrieLogs);
        $this->assertSame($log->id, $user->pastrieLogs->first()->id);
    }
}
