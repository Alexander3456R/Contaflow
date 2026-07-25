<?php

/**
 * Front controller de la aplicación.
 *
 * Punto de entrada para todas las peticiones HTTP. Verifica el modo
 * mantenimiento, registra el autoloader de Composer, arranca Laravel
 * y maneja la petición.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Verificar si la aplicación está en modo mantenimiento
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Registrar el autoloader de Composer
require __DIR__ . '/../vendor/autoload.php';

// Inicializar Laravel y procesar la petición
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
