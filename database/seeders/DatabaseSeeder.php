<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Orden de siembra: primero preguntas de seguridad (dependencia de usuarios),
        // luego usuarios, transacciones y finalmente bitácora de auditoría
        $this->call([
            SecurityQuestionSeeder::class,
            UserSeeder::class,
            TransactionSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
