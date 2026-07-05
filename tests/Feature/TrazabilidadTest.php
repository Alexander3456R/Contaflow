<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrazabilidadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected(): void
    {
        $response = $this->get(route('trazabilidad'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_trazabilidad(): void
    {
        $user = User::factory()->create();
        AuditLog::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('trazabilidad'));

        $response->assertStatus(200);
    }
}
