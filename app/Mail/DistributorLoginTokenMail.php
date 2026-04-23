<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

//Email personalizado
class DistributorLoginTokenMail extends Mailable
{
    use Queueable, SerializesModels;  //Queueable: permite enviar el correo en cola. SerializesModels: convierte modelos en formato seguro

    public int $token;  //codigo que se le va enviar al usuario

    /**
     * Recibe el código y lo guarda para usarlo en el correo
     */
    public function __construct(int $token)
    {
        $this->token = $token;
    }

    /**
     * el correo contiene este texto y lleva a esta vista
     */
    public function build()
    {
        return $this->subject('Código de acceso')
            ->view('emails.distributors.login-token');
    }
}