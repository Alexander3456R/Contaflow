<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReporteTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected(): void
    {
        $response = $this->get(route('reportes'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_reportes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reportes'));

        $response->assertStatus(200);
    }

    public function test_export_csv_returns_file(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reportes.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition', 'attachment; filename=reporte-financiero-' . now()->format('Y-m-d') . '.xlsx');
    }
}
