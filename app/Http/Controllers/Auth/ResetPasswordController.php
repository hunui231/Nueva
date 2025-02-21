<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ResetPasswordController extends Controller
{

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // Procesa el restablecimiento de contraseña
    public function reset(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Intenta restablecer la contraseña
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Actualiza la contraseña del usuario
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                // Elimina el token de restablecimiento
                $user->setRememberToken(Str::random(60));

                // Dispara el evento de restablecimiento de contraseña
                event(new PasswordReset($user));
            }
        );

        // Redirige con un mensaje de éxito o error
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
