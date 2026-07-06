<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

final class SecurityHeaders
{
    private const array HEADERS = [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-Content-Type-Options' => 'nosniff',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), interest-cohort=()',
        'X-Permitted-Cross-Domain-Policies' => 'none',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(16));
        View::share('cspNonce', $nonce);

        $response = $next($request);

        foreach (self::HEADERS as $key => $value) {
            $response->headers->set($key, $value);
        }

        if (! app()->isLocal()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $csp = "default-src 'self'; "
            . "script-src 'self' 'nonce-{$nonce}' https://cdn.tailwindcss.com; "
            . "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com; "
            . "img-src 'self' data: blob:; "
            . "font-src 'self' https://fonts.gstatic.com; "
            . "connect-src 'self'; "
            . "frame-ancestors 'none'; "
            . "form-action 'self'";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
