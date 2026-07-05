<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
        return view('perfil', ['user' => Auth::user()]);
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
}
