<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'created',
                'entity_type' => 'transaction',
                'entity_id' => 1,
                'description' => "Movimiento creado: Pago de servicios - \$350.00",
                'new_values' => ['amount' => 350, 'description' => 'Pago de servicios'],
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'updated',
                'entity_type' => 'transaction',
                'entity_id' => 1,
                'description' => "Movimiento editado: Pago de servicios",
                'old_values' => ['amount' => 300, 'description' => 'Pago'],
                'new_values' => ['amount' => 350, 'description' => 'Pago de servicios'],
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'deleted',
                'entity_type' => 'transaction',
                'entity_id' => 2,
                'description' => "Movimiento eliminado: Factura duplicada",
                'old_values' => ['amount' => 1200, 'reference' => 'FACT-8821'],
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'exported',
                'entity_type' => 'report',
                'entity_id' => null,
                'description' => "Reporte exportado: Estado de Resultados Q3",
            ]);
        }
    }
}
