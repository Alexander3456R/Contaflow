<?php

/**
 * Seeder principal de la base de datos.
 *
 * Orquesta la siembra de datos en orden: primero preguntas de seguridad,
 * luego usuarios, transacciones y finalmente registros de auditoría.
 */

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/** Ejecuta todos los seeders en orden de dependencia */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SecurityQuestionSeeder::class,
            UserSeeder::class,
            TransactionSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
