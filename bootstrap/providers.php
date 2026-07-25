<?php

/**
 * Proveedores de servicios registrados.
 *
 * Lista los service providers que Laravel cargará durante el
 * arranque de la aplicación.
 */

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
];
