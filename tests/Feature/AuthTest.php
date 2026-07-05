<?php

namespace Tests\Feature;

use App\Models\SecurityQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SecurityQuestionSeeder::class);
    }

    public function test_login_page_loads(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    public function test_register_page_loads(): void
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $questions = SecurityQuestion::take(3)->get();

        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Test@1234',
            'password_confirmation' => 'Test@1234',
            'question_1' => $questions[0]->id,
            'question_2' => $questions[1]->id,
            'question_3' => $questions[2]->id,
            'answer_1' => 'Respuesta 1',
            'answer_2' => 'Respuesta 2',
            'answer_3' => 'Respuesta 3',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_forgot_password_page_loads(): void
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
    }

    public function test_authenticated_user_redirected_from_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));
        $response->assertRedirect(route('dashboard'));
    }
}
