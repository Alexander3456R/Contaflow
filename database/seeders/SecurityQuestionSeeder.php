<?php

/**
 * Seeder de preguntas de seguridad.
 *
 * Siembra 10 preguntas de seguridad predefinidas en español para
 * el flujo de recuperación de contraseña.
 */

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SecurityQuestion;
use Illuminate\Database\Seeder;

/** Siembra las preguntas de seguridad predefinidas */
class SecurityQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            '¿Cuál es el nombre de tu primera mascota?',
            '¿Cuál es el nombre de tu ciudad natal?',
            '¿Cuál es el nombre de tu mejor amigo de la infancia?',
            '¿Cuál es tu comida favorita?',
            '¿Cuál es el nombre de tu profesor favorito?',
            '¿Cuál es tu película favorita?',
            '¿Cómo se llamaba tu escuela primaria?',
            '¿Cuál es tu deporte favorito?',
            '¿Cuál es el segundo apellido de tu madre?',
            '¿Cuál es tu libro favorito?',
        ];

        foreach ($questions as $q) {
            SecurityQuestion::create(['question' => $q]);
        }
    }
}
