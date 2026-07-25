<?php

/**
 * Definición de rutas de consola.
 *
 * Define comandos personalizados de Artisan.
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Mostrar una cita inspiradora');
