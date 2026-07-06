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

            // Genera ~80 transacciones por usuario desde enero 2024 hasta la fecha actual
            $start = now()->setDate(2024, 1, 1);
            $end = now();
            $daysRange = (int) $start->diffInDays($end);
            $count = 80;

            $dates = collect();
            for ($i = 0; $i < $count; $i++) {
                $randomDay = fake()->numberBetween(0, $daysRange);
                $dates->push($start->copy()->addDays($randomDay));
            }
            $dates = $dates->sort()->values();

            foreach ($dates as $date) {
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
                    'balance' => $balance,
                    'transaction_date' => $date->format('Y-m-d'),
                    'category' => fake()->randomElement(self::CATEGORIES),
                    'reference' => fake()->optional(0.4)->bothify('FACT-####'),
                ]);
            }
        }
    }
}
