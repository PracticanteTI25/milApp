<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class DistributorResetPasswordNotification extends ResetPassword
{
    /**
     * Generar el correo con la ruta correcta
     */
    public function toMail($notifiable)
    {
        $url = url(route(
            'distribuidores.password.reset',
            [
                'token' => $this->token,
                'email' => $notifiable->email,
            ],
            false
        ));

        return (new MailMessage)
            ->subject('Restablecer contraseña')
            ->line('Recibimos una solicitud para restablecer tu contraseña.')
            ->action('Restablecer contraseña', $url)
            ->line('Si no solicitaste el cambio, ignora este mensaje.');
    }
}