<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class DistributorForgotPasswordController extends Controller
{
    // Muestra la vista donde el usuario escribe su correo
    public function showLinkRequestForm()
    {
        return view('distribuidores.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        //indica que está trabajando con distribuidores, no usuarios normales
        $status = Password::broker('distributors')
            ->sendResetLink($request->only('email'));       //Genera un token único, lo guarda en la base de datos (password_resets) y envia el correo

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))    //vuelve al formulario con mensaje de exito
            : back()->withErrors(['email' => __($status)]);     //muestra error
    }
}