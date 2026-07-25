<?php

/**
 * Controlador base.
 *
 * Todos los controladores de la aplicación deben extender esta clase
 * abstracta, que proporciona la funcionalidad de autorización mediante
 * AuthorizesRequests.
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/** Controlador base abstracto con autorización */
abstract class Controller
{
    use AuthorizesRequests;
}
