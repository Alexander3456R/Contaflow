<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovimientoTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected(): void
    {
        $response = $this->get(route('movimientos'));
        $response->assertRedirect(route('login'));
    }

    public function test_index_shows_transactions(): void
    {
        $user = User::factory()->create();
        Transaction::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('movimientos'));

        $response->assertStatus(200);
    }

    public function test_store_creates_transaction(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('movimientos.store'), [
            'description' => 'Test transaction',
            'type' => 'credito',
            'amount' => 500.00,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('movimientos'));
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'description' => 'Test transaction',
            'amount' => 500.00,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('movimientos.store'), []);

        $response->assertSessionHasErrors(['description', 'type', 'amount', 'transaction_date']);
    }

    public function test_destroy_deletes_transaction(): void
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('movimientos.destroy', $transaction));

        $response->assertRedirect(route('movimientos'));
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    public function test_user_cannot_delete_others_transaction(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->delete(route('movimientos.destroy', $transaction));

        $response->assertStatus(403);
    }

    public function test_edit_returns_json(): void
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('movimientos.edit', $transaction));

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $transaction->id]);
        $response->assertJsonFragment(['description' => $transaction->description]);
    }

    public function test_edit_denied_for_other_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->get(route('movimientos.edit', $transaction));

        $response->assertStatus(403);
    }

    public function test_update_modifies_transaction(): void
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'description' => 'Original',
            'type' => 'credito',
            'amount' => 100.00,
        ]);

        $response = $this->actingAs($user)->put(route('movimientos.update', $transaction), [
            'description' => 'Updated',
            'type' => 'debito',
            'amount' => 50.00,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('movimientos'));
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'description' => 'Updated',
            'amount' => 50.00,
        ]);
    }
}
