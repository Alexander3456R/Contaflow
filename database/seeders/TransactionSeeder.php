<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    private const CATEGORIES = [
        'Nómina', 'Servicios', 'Oficina', 'Impuestos',
        'Consultoría', 'Ventas', 'Suscripciones', 'Marketing',
    ];

    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $balance = 0;

            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                $type = fake()->randomElement(['credito', 'debito']);

                if ($type === 'credito') {
                    $amount = fake()->randomFloat(2, 100, 8500);
                    $balance += $amount;
                } else {
                    $amount = fake()->randomFloat(2, 15, 3200);
                    $balance -= $amount;
                }

                Transaction::create([
                    'user_id' => $user->id,
                    'description' => fake()->sentence(3),
                    'type' => $type,
                    'amount' => $amount,
                    'balance' => max($balance, 0),
                    'transaction_date' => $date->format('Y-m-d'),
                    'category' => fake()->randomElement(self::CATEGORIES),
                    'reference' => fake()->optional(0.4)->bothify('FACT-####'),
                ]);
            }
        }
    }
}
