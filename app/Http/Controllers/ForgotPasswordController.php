<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Exception;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validar el correo electrónico
        $request->validate(['email' => 'required|email']);

        // Intentar enviar el correo electrónico
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Redirigir con un mensaje de éxito o error
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

}