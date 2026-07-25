<?php

/**
 * Tests de ejemplo (smoke tests).
 *
 * Pruebas básicas para verificar que la aplicación responde
 * correctamente en rutas públicas.
 */

namespace Tests\Feature;

use Tests\TestCase;

/** Pruebas de humo para rutas públicas */
class ExampleTest extends TestCase
{
    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_dashboard_redirects_when_unauthenticated(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
