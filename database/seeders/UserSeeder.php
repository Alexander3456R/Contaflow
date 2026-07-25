<?php

/**
 * Seeder de usuarios.
 *
 * Crea 2 usuarios fijos (Lic. María Delgado y Admin ContaFlow) más
 * 3 usuarios aleatorios, cada uno con 3 respuestas de seguridad.
 */

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SecurityQuestion;
use App\Models\User;
use App\Models\UserSecurityAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/** Siembra usuarios y sus respuestas de seguridad */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $questions = SecurityQuestion::all();
        $answers = ['Respuesta 1', 'Respuesta 2', 'Respuesta 3', 'Mi respuesta', 'Otra respuesta'];

        $usersData = [
            ['name' => 'Lic. María Delgado', 'email' => 'maria@contaflow.com', 'password' => Hash::make('password')],
            ['name' => 'Admin ContaFlow', 'email' => 'admin@contaflow.com', 'password' => Hash::make(env('ADMIN_PASSWORD'))],
        ];

        foreach ($usersData as $data) {
            $user = User::factory()->create($data);
            $this->createSecurityAnswers($user, $questions, $answers);
        }

        User::factory()->count(3)->create()->each(function ($user) use ($questions, $answers) {
            $this->createSecurityAnswers($user, $questions, $answers);
        });
    }

    /** Asigna 3 respuestas de seguridad aleatorias a un usuario */
    private function createSecurityAnswers(User $user, $questions, array $answers): void
    {
        $selected = $questions->random(3);
        foreach ($selected as $i => $q) {
            UserSecurityAnswer::create([
                'user_id' => $user->id,
                'security_question_id' => $q->id,
                'answer' => Hash::make($answers[$i] ?? 'default'),
            ]);
        }
    }
}
