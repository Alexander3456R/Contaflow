<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SecurityQuestion;
use App\Models\User;
use App\Models\UserSecurityAnswer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Registro de nuevos usuarios con preguntas de seguridad.
 */
class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro con preguntas de seguridad.
     */
    public function showRegisterForm(): View
    {
        $questions = SecurityQuestion::all();

        return view('auth.register', compact('questions'));
    }

    /**
     * Valida datos, crea el usuario, guarda respuestas de seguridad e inicia sesión.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'question_1' => ['required', 'exists:security_questions,id'],
            'question_2' => ['required', 'exists:security_questions,id', 'different:question_1'],
            'question_3' => ['required', 'exists:security_questions,id', 'different:question_1', 'different:question_2'],
            'answer_1' => ['required', 'string', 'max:255'],
            'answer_2' => ['required', 'string', 'max:255'],
            'answer_3' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        UserSecurityAnswer::create([
            'user_id' => $user->id,
            'security_question_id' => $request->question_1,
            'answer' => Hash::make($request->answer_1),
        ]);

        UserSecurityAnswer::create([
            'user_id' => $user->id,
            'security_question_id' => $request->question_2,
            'answer' => Hash::make($request->answer_2),
        ]);

        UserSecurityAnswer::create([
            'user_id' => $user->id,
            'security_question_id' => $request->question_3,
            'answer' => Hash::make($request->answer_3),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
