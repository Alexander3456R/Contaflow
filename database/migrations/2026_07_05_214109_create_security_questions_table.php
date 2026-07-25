<?php

/**
 * Migración: Creación de tabla security_questions.
 *
 * Crea la tabla de preguntas de seguridad utilizadas en el flujo
 * de recuperación de contraseña.
 */

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Ejecuta la migración */
    public function up(): void
    {
        Schema::create('security_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->timestamps();
        });
    }

    /** Revierte la migración */
    public function down(): void
    {
        Schema::dropIfExists('security_questions');
    }
};
