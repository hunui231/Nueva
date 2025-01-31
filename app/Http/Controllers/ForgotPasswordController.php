<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;

class ForgotPasswordController extends Controller
{
    public function mostrarFormulario()
    {
        return view('auth.forgot-password');
    }

    public function enviarCorreo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        try {
            Mail::raw("Se ha recibido una solicitud de recuperación de contraseña para: $email", function ($message) use ($email) {
                $message->to('a.sistemas2@conplasa.com.mx') 

                        ->subject('Solicitud de recuperación de contraseña');
            });

            // Redirige con mensaje de éxito
            return back()->with('status', 'El correo ha sido enviado correctamente.');

        } catch (Exception $e) {
            // Si ocurre un error, se muestra el error
            return back()->with('error', 'Ocurrió un error al intentar enviar el correo: ' . $e->getMessage());
        }
    }
}