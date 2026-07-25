<?php

/**
 * Migración: Creación de tabla audit_logs.
 *
 * Crea la tabla para el registro de auditoría, almacenando acciones
 * del usuario sobre entidades del sistema con valores anteriores
 * y nuevos en formato JSON.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Ejecuta la migración */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
        });
    }

    /** Revierte la migración */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
