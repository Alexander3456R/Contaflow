<?php

/**
 * Configuración de sesión.
 *
 * Define el driver de sesión, tiempo de vida, cifrado, ubicación de
 * archivos, conexión DB, tabla, cookie y políticas Same-Site.
 */

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Driver de sesión por defecto
    |--------------------------------------------------------------------------
    |
    | Determina el driver de sesión utilizado para las peticiones entrantes.
    |
    | Soportados: "file", "cookie", "database", "memcached",
    |             "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Tiempo de vida de la sesión
    |--------------------------------------------------------------------------
    |
    | Número de minutos que la sesión permanece activa antes de expirar.
    | Si se activa expire_on_close, la sesión expira al cerrar el navegador.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Cifrado de sesión
    |--------------------------------------------------------------------------
    |
    | Si se activa, todos los datos de sesión se cifran antes de almacenarse.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Ubicación de archivos de sesión
    |--------------------------------------------------------------------------
    |
    | Cuando se usa el driver "file", los archivos de sesión se almacenan
    | en esta ubicación.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Conexión de base de datos para sesión
    |--------------------------------------------------------------------------
    |
    | Al usar los drivers "database" o "redis", especifica la conexión
    | que debe utilizarse.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Tabla de sesiones en base de datos
    |--------------------------------------------------------------------------
    |
    | Al usar el driver "database", especifica la tabla donde se almacenan
    | las sesiones.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Almacén de caché para sesión
    |--------------------------------------------------------------------------
    |
    | Al usar backends de sesión basados en caché, define el almacén de
    | caché que se usará para los datos de sesión.
    |
    | Afecta a: "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Lottería de limpieza de sesiones
    |--------------------------------------------------------------------------
    |
    | Algunos drivers deben barrer manualmente su almacenamiento para
    | eliminar sesiones viejas. 2 de cada 100 solicitudes activan la
    | limpieza.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Nombre de la cookie de sesión
    |--------------------------------------------------------------------------
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')) . '-session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Ruta de la cookie de sesión
    |--------------------------------------------------------------------------
    |
    | Determina la ruta para la cual la cookie está disponible.
    | Normalmente es la raíz de la aplicación.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Dominio de la cookie de sesión
    |--------------------------------------------------------------------------
    |
    | Determina los dominios y subdominios a los que la cookie está
    | disponible.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Cookie solo HTTPS
    |--------------------------------------------------------------------------
    |
    | Si se activa, la cookie solo se enviará al servidor si la conexión
    | es HTTPS.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | Cookie solo HTTP
    |--------------------------------------------------------------------------
    |
    | Si se activa, JavaScript no podrá acceder al valor de la cookie.
    | Solo será accesible mediante el protocolo HTTP.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Cookies Same-Site
    |--------------------------------------------------------------------------
    |
    | Determina cómo se comportan las cookies ante peticiones entre sitios.
    | Ayuda a mitigar ataques CSRF.
    |
    | Ver: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
    |
    | Soportados: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Cookies particionadas
    |--------------------------------------------------------------------------
    |
    | Si se activa, vincula la cookie al sitio de nivel superior en un
    | contexto entre sitios. Requiere "secure" y Same-Site="none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Serialización de sesión
    |--------------------------------------------------------------------------
    |
    | Controla la estrategia de serialización de datos de sesión.
    | "json" es el valor por defecto. "php" permite objetos PHP pero
    | puede ser vulnerable a ataques de serialización si APP_KEY se filtra.
    |
    | Soportados: "json", "php"
    |
    */

    'serialization' => 'json',

];
