<?php

/**
 * Policy de autorización para transacciones.
 *
 * Define las reglas de acceso: un usuario solo puede ver, editar o
 * eliminar sus propias transacciones. Todos los usuarios autenticados
 * pueden crear transacciones.
 */

declare(strict_types=1);

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

/** Autorización para operaciones sobre transacciones */
class TransactionPolicy
{
    /** Verifica que el usuario sea el propietario de la transacción */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }

    /** Todos los usuarios autenticados pueden crear transacciones */
    public function create(User $user): bool
    {
        return true;
    }

    /** Verifica que el usuario sea el propietario de la transacción */
    public function update(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }

    /** Verifica que el usuario sea el propietario de la transacción */
    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }
}
