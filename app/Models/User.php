<?php

/**
 * Modelo: Usuario.
 *
 * Representa un usuario del sistema con autenticación, notificaciones
 * y relaciones con transacciones y respuestas de seguridad.
 */

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
/** Representa un usuario del sistema */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /** Define los casts de los atributos */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Respuestas de seguridad del usuario */
    public function securityAnswers(): HasMany
    {
        return $this->hasMany(UserSecurityAnswer::class);
    }
}
