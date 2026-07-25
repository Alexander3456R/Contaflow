<?php

/**
 * Migración: Creación de tabla user_security_answers.
 *
 * Crea la tabla que almacena las respuestas cifradas de los usuarios
 * a las preguntas de seguridad. Relaciona users con security_questions
 * con una restricción unique por par usuario-pregunta.
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
        Schema::create('user_security_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('security_question_id')->constrained()->onDelete('cascade');
            $table->string('answer');
            $table->timestamps();

            $table->unique(['user_id', 'security_question_id']);
        });
    }

    /** Revierte la migración */
    public function down(): void
    {
        Schema::dropIfExists('user_security_answers');
    }
};
