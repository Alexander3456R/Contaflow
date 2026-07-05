<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip()));

        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));

        RateLimiter::for('password-reset', fn (Request $request) => Limit::perMinute(3)->by($request->input('email') . '|' . $request->ip()));

        RateLimiter::for('password-questions', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));

        RateLimiter::for('movimientos', fn (Request $request) => Limit::perMinute(10)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('movimientos-delete', fn (Request $request) => Limit::perMinute(5)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('perfil', fn (Request $request) => Limit::perMinute(5)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('export', fn (Request $request) => Limit::perMinute(3)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('notificaciones', fn (Request $request) => Limit::perMinute(10)->by($request->user()?->id ?: $request->ip()));
    }
}
