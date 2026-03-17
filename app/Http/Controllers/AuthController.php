<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Mostrar formulario login
     */
    public function showLogin()
    {
        // Si ya hay sesión activa, no permitir volver al login
        if (session()->has('user')) {
            return redirect('/admin');
        }

        return view('login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        // Validamos datos
        $request->validate([
            'user' => 'required',
            'password' => 'required'
        ]);

        // SIMULACIÓN (luego LDAP)
        if ($request->user == 'admin' && $request->password == '123') {

            // Guardamos sesión
            session([
                'user' => [
                    'nombre' => $request->user,
                    'rol' => 'Admin' // temporal por ahora
                ]
            ]);

            return redirect('/admin');
        }

        // Credenciales incorrectas
        return back()->withErrors([
            'error' => 'Credenciales incorrectas'
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        session()->flush();  //elimina toda la sesion

        return redirect('/login');
    }
}