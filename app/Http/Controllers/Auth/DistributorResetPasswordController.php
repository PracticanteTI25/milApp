<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DistributorResetPasswordController extends Controller
{
    /**
     * Mostrar formulario para nueva contraseña
     */

    // vista del formulario
    public function showResetForm(Request $request, $token = null)
    {
        return view('distribuidores.auth.passwords.reset', [
            'token' => $token,     //parar enviar a la vista el token que viene del correo
            'email' => $request->email,   
        ]);
    }

    /**
     * Procesar el cambio de contraseña
     */

    // validacion
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::broker('distributors')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($distributor, $password) {       //funcion que pasa el distribuidor encontrado con su nueva contraseña
                //aqui se guarda la nueva contraseña
                $distributor->forceFill([
                    'password' => Hash::make($password),   //encripta la contraseña
                    'remember_token' => Str::random(60),    //genera nuevo token
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('distribuidores.login')->with('status', __($status))  //devuelve al login con mensaje de exito
            : back()->withErrors(['email' => __($status)]);      //regresa al formulario con error
    }
}