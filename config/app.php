<?php

/**
 * Configuración general de la aplicación.
 *
 * Define el nombre, entorno, modo depuración, URL base, zona horaria,
 * locale, clave de cifrado y controlador de modo mantenimiento.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Nombre de la aplicación
    |--------------------------------------------------------------------------
    |
    | Este valor es el nombre de tu aplicación, usado cuando el framework
    | necesita mostrar el nombre en notificaciones u otros elementos de UI.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Entorno de la aplicación
    |--------------------------------------------------------------------------
    |
    | Determina el entorno en el que se ejecuta la aplicación (local,
    | producción, etc.). Se define en el archivo .env.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Modo depuración
    |--------------------------------------------------------------------------
    |
    | Cuando está activado, se muestran mensajes de error detallados.
    | En producción debe estar desactivado.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL de la aplicación
    |--------------------------------------------------------------------------
    |
    | Esta URL es usada por la consola para generar URLs correctamente
    | al usar Artisan. Debe apuntar a la raíz de la aplicación.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Zona horaria
    |--------------------------------------------------------------------------
    |
    | Zona horaria por defecto para la aplicación, usada por las funciones
    | de fecha y hora de PHP.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'America/Mexico_City'),

    /*
    |--------------------------------------------------------------------------
    | Configuración de locale
    |--------------------------------------------------------------------------
    |
    | El locale determina el idioma por defecto usado por los métodos de
    | traducción/localización de Laravel.
    |
    */

    'locale' => env('APP_LOCALE', 'es'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Clave de cifrado
    |--------------------------------------------------------------------------
    |
    | Esta clave es usada por los servicios de cifrado de Laravel. Debe ser
    | una cadena aleatoria de 32 caracteres. Debe definirse antes de
    | desplegar la aplicación.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Controlador de modo mantenimiento
    |--------------------------------------------------------------------------
    |
    | Determina el controlador usado para gestionar el estado de
    | "modo mantenimiento". El controlador "cache" permite gestionarlo
    | desde múltiples máquinas.
    |
    | Controladores soportados: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
