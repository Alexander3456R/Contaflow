<?php

/**
 * Modelo: Registro de auditoría.
 *
 * Almacena un registro de todas las acciones realizadas por los usuarios
 * sobre las entidades del sistema (transacciones, perfil, etc.), incluyendo
 * los valores anteriores y nuevos para trazabilidad.
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Representa un registro de auditoría */
class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'entity_type', 'entity_id',
        'description', 'old_values', 'new_values',
    ];

    /** Define los casts de los atributos */
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'read_at' => 'datetime',
        ];
    }

    /** Usuario que realizó la acción */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
