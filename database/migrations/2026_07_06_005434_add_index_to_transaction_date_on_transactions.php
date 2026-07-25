<?php

/**
 * Migración: Índice en transaction_date.
 *
 * Agrega un índice a la columna transaction_date en la tabla
 * transactions para optimizar las consultas por rango de fechas
 * y ordenamiento cronológico.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Ejecuta la migración */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('transaction_date');
        });
    }

    /** Revierte la migración */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['transaction_date']);
        });
    }
};
