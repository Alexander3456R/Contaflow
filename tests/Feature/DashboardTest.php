<?php

/**
 * Tests del dashboard.
 *
 * Verifica que los usuarios no autenticados sean redirigidos y que
 * los usuarios autenticados vean el panel con saldo e ingresos.
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Pruebas del panel principal */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected_from_dashboard(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Saldo Actual');
        $response->assertSee('Ingresos');
        $response->assertSee('Egresos');
    }
}
