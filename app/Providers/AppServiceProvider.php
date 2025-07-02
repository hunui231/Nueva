<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject('Restablece tu contraseña en ' . config('app.name'))
                ->greeting('¡Hola!')
                ->line('Recibiste este correo porque solicitaste un restablecimiento de contraseña.')
                ->action('Restablecer contraseña', url(route('password.reset', $token)))
                ->line('Este enlace expira en 60 minutos.')
                ->salutation('Saludos, Equipo ' . config('app.name'));
        });
    }
}
