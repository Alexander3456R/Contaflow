<?php

/**
 * Configuración de sistemas de archivos.
 *
 * Define el disco por defecto, los discos disponibles (local, público,
 * S3) y los enlaces simbólicos.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Disco por defecto
    |--------------------------------------------------------------------------
    |
    | Especifica el disco de sistema de archivos usado por el framework
    | cuando no se especifica otro.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Discos de sistema de archivos
    |--------------------------------------------------------------------------
    |
    | Configura los discos disponibles. Puedes configurar múltiples discos
    | para el mismo driver.
    |
    | Drivers soportados: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enlaces simbólicos
    |--------------------------------------------------------------------------
    |
    | Configura los enlaces simbólicos creados al ejecutar el comando
    | `storage:link` de Artisan.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
