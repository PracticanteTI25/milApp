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
        // Validamos datos del formulario
        $request->validate([
            'user' => 'required',
            'password' => 'required'
        ]);

        // SIMULACIÓN (luego LDAP o base de datos)
        if ($request->user == 'admin' && $request->password == '123') {

            // Guardamos sesión
            session([
                'user' => [
                    'nombre' => $request->user,
                    'rol' => 'Admin'
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
        // Obtener usuario antes de destruir sesión
        $usuario = session('user.nombre');

        // Eliminar toda la sesión
        session()->flush();

        return redirect('/login');
    }
}