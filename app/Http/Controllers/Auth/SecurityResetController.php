<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSecurityAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Restablecimiento de contraseña mediante preguntas de seguridad.
 */
class SecurityResetController extends Controller
{
    /**
     * Muestra el formulario para ingresar el correo electrónico.
     */
    public function showEmailForm(): View
    {
        return view('auth.security-email');
    }

    /**
     * Verifica que el correo exista y tenga preguntas de seguridad configuradas.
     */
    public function verifyEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

        $user = User::where('email', $request->email)->first();

        if ($user->securityAnswers()->count() < 3) {
            Log::warning('Restablecimiento de contraseña bloqueado — usuario sin preguntas de seguridad', [
                'user_id' => $user->id,
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return back()->withErrors(['email' => 'El usuario no tiene preguntas de seguridad configuradas.']);
        }

        Session::put('reset_user_id', $user->id);

        return redirect()->route('password.questions');
    }

    /**
     * Muestra las preguntas de seguridad del usuario.
     */
    public function showQuestionsForm(): View|RedirectResponse
    {
        $userId = Session::get('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request');
        }

        $answers = UserSecurityAnswer::with('question')
            ->where('user_id', $userId)
            ->get();

        return view('auth.security-questions', compact('answers'));
    }

    /**
     * Verifica que las respuestas de seguridad sean correctas.
     */
    public function verifyQuestions(Request $request): RedirectResponse
    {
        $userId = Session::get('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'answer_1' => ['required', 'string'],
            'answer_2' => ['required', 'string'],
            'answer_3' => ['required', 'string'],
        ]);

        $answers = UserSecurityAnswer::where('user_id', $userId)->get();

        $allCorrect = true;

        foreach ($answers as $i => $ans) {
            $inputKey = 'answer_' . ($i + 1);
            if (!Hash::check($request->$inputKey, $ans->answer)) {
                $allCorrect = false;
                break;
            }
        }

        if (!$allCorrect) {
            Log::warning('Respuestas de seguridad incorrectas', [
                'user_id' => $userId,
                'ip' => $request->ip(),
            ]);

            return back()->withErrors(['answers' => 'Una o más respuestas son incorrectas.']);
        }

        Session::put('reset_verified', true);

        return redirect()->route('password.reset');
    }

    /**
     * Muestra el formulario para ingresar la nueva contraseña (solo si está verificado).
     */
    public function showResetForm(): View|RedirectResponse
    {
        if (!Session::get('reset_verified') || !Session::get('reset_user_id')) {
            return redirect()->route('password.request');
        }

        return view('auth.security-reset');
    }

    /**
     * Actualiza la contraseña y limpia los datos temporales de la sesión.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        if (!Session::get('reset_verified') || !Session::get('reset_user_id')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::findOrFail(Session::get('reset_user_id'));
        $user->password = Hash::make($request->password);
        $user->save();

        Session::forget(['reset_user_id', 'reset_verified']);

        return redirect()->route('login')->with('status', 'Contraseña actualizada exitosamente.');
    }
}
