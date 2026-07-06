<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SecurityQuestion;
use App\Models\UserSecurityAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Gestión del perfil del usuario autenticado.
 */
class PerfilController extends Controller
{
    /**
     * Muestra el formulario de perfil del usuario.
     */
    public function index(): View
    {
        $user = Auth::user();
        $questions = SecurityQuestion::all();
        $userAnswers = UserSecurityAnswer::with('question')
            ->where('user_id', $user->id)
            ->get();
        return view('perfil', compact('user', 'questions', 'userAnswers'));
    }

    /**
     * Actualiza el nombre y correo electrónico del usuario.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('perfil')->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Cambia la contraseña del usuario tras validar la actual.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('perfil')->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Actualiza las preguntas y respuestas de seguridad del usuario.
     */
    public function updateSecurity(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'question_1' => ['required', 'exists:security_questions,id'],
            'question_2' => ['required', 'exists:security_questions,id', 'different:question_1'],
            'question_3' => ['required', 'exists:security_questions,id', 'different:question_1', 'different:question_2'],
            'answer_1' => ['required', 'string', 'max:255'],
            'answer_2' => ['required', 'string', 'max:255'],
            'answer_3' => ['required', 'string', 'max:255'],
        ]);

        $user->securityAnswers()->delete();

        foreach (range(1, 3) as $i) {
            UserSecurityAnswer::create([
                'user_id' => $user->id,
                'security_question_id' => $request->{"question_$i"},
                'answer' => Hash::make($request->{"answer_$i"}),
            ]);
        }

        return redirect()->route('perfil')->with('success', 'Preguntas de seguridad actualizadas exitosamente.');
    }
}
