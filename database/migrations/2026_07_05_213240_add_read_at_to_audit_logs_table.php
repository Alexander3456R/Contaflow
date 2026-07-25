<?php

/**
 * Migración: Agregar columna read_at a audit_logs.
 *
 * Añade la columna read_at (timestamp) a la tabla de auditoría para
 * marcar cuándo el usuario ha leído/visto una notificación de evento.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Ejecuta la migración */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable()->after('new_values');
        });
    }

    /** Revierte la migración */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn('read_at');
        });
    }
};
