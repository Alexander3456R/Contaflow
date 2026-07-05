<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['credito', 'debito']);
        $amount = fake()->randomFloat(2, 50, 5000);

        return [
            'user_id' => User::factory(),
            'description' => fake()->sentence(3),
            'type' => $type,
            'amount' => $amount,
            'balance' => fake()->randomFloat(2, 1000, 50000),
            'transaction_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'category' => fake()->randomElement(['Nómina', 'Servicios', 'Oficina', 'Ventas']),
            'reference' => fake()->optional(0.3)->bothify('FACT-####'),
        ];
    }
}
