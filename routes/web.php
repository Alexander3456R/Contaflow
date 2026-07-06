<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SecurityResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TrazabilidadController;
use Illuminate\Support\Facades\Route;

// Rutas para usuarios no autenticados: inicio de sesión, registro y restablecimiento de contraseña
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:register');
    Route::get('/forgot-password', [SecurityResetController::class, 'showEmailForm'])->name('password.request');
    Route::post('/forgot-password', [SecurityResetController::class, 'verifyEmail'])->middleware('throttle:password-reset')->name('password.email');
    Route::get('/reset-password/questions', [SecurityResetController::class, 'showQuestionsForm'])->name('password.questions');
    Route::post('/reset-password/questions', [SecurityResetController::class, 'verifyQuestions'])->middleware('throttle:password-questions')->name('password.questions.verify');
    Route::get('/reset-password', [SecurityResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [SecurityResetController::class, 'updatePassword'])->middleware('throttle:password-reset')->name('password.update');
});

// Rutas para usuarios autenticados: dashboard, movimientos, reportes, perfil, trazabilidad y notificaciones
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos');
    Route::post('/movimientos', [MovimientoController::class, 'store'])->middleware('throttle:movimientos')->name('movimientos.store');
    Route::get('/movimientos/{movimiento}', [MovimientoController::class, 'show'])->name('movimientos.show');
    Route::get('/movimientos/{movimiento}/edit', [MovimientoController::class, 'edit'])->name('movimientos.edit');
    Route::put('/movimientos/{movimiento}', [MovimientoController::class, 'update'])->middleware('throttle:movimientos')->name('movimientos.update');
    Route::delete('/movimientos/{movimiento}', [MovimientoController::class, 'destroy'])->middleware('throttle:movimientos-delete')->name('movimientos.destroy');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes');
    Route::get('/reportes/exportar', [ReporteController::class, 'exportCsv'])->middleware('throttle:export')->name('reportes.export');

    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::put('/perfil', [PerfilController::class, 'update'])->middleware('throttle:perfil')->name('perfil.update');
    Route::put('/perfil/password', [PerfilController::class, 'updatePassword'])->middleware('throttle:perfil')->name('perfil.password');
    Route::put('/perfil/seguridad', [PerfilController::class, 'updateSecurity'])->middleware('throttle:perfil')->name('perfil.security');

    Route::get('/trazabilidad', [TrazabilidadController::class, 'index'])->name('trazabilidad');

    Route::post('/notificaciones/leer', function () {
        \App\Models\AuditLog::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    })->middleware('throttle:notificaciones')->name('notificaciones.leer');
});

// Redirige la raíz al dashboard para usuarios autenticados
Route::get('/', fn () => redirect()->route('dashboard'));
