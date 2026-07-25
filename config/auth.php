<?php

/**
 * Configuración de autenticación.
 *
 * Define los guards, proveedores de usuarios, reseteo de contraseñas
 * y tiempo de expiración de confirmación de contraseña.
 */

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Valores por defecto de autenticación
    |--------------------------------------------------------------------------
    |
    | Define el "guard" y "broker" de reseteo de contraseña por defecto.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards de autenticación
    |--------------------------------------------------------------------------
    |
    | Define cada guard de autenticación. La configuración por defecto usa
    | almacenamiento en sesión con el proveedor Eloquent.
    |
    | Soportado: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Proveedores de usuarios
    |--------------------------------------------------------------------------
    |
    | Define cómo se recuperan los usuarios de la base de datos u otro
    | sistema de almacenamiento. Por defecto se utiliza Eloquent.
    |
    | Soportado: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reseteo de contraseñas
    |--------------------------------------------------------------------------
    |
    | Configura el comportamiento del reseteo de contraseñas: la tabla para
    | almacenar tokens, el tiempo de expiración (minutos) y el throttling
    | (segundos que debe esperar el usuario antes de generar más tokens).
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de expiración de confirmación de contraseña
    |--------------------------------------------------------------------------
    |
    | Define los segundos antes de que expire la ventana de confirmación
    | de contraseña y se le pida al usuario ingresarla nuevamente.
    | Por defecto: 3 horas (10800 segundos).
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
