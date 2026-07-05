<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected(): void
    {
        $response = $this->get(route('perfil'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_perfil(): void
    {
        $user = User::factory()->create(['name' => 'Test User']);

        $response = $this->actingAs($user)->get(route('perfil'));

        $response->assertStatus(200);
        $response->assertSee('Test User');
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('perfil.update'), [
            'name' => 'New Name',
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('perfil'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }
}
