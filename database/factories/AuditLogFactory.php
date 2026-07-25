<?php

/**
 * Fábrica: AuditLogFactory.
 *
 * Genera datos de prueba para el modelo AuditLog con acción
 * aleatoria y descripción ficticia.
 */

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** Genera registros de auditoría de prueba */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        $actions = ['created', 'updated', 'deleted', 'exported'];

        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement($actions),
            'entity_type' => 'transaction',
            'entity_id' => fake()->numberBetween(1, 100),
            'description' => fake()->sentence(4),
            'old_values' => null,
            'new_values' => null,
        ];
    }
}
