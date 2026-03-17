<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCustom
{
    /**
     * Este método se ejecuta ANTES de entrar a la ruta protegida
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificamos si existe sesión de usuario
        // session('user') lo crearemos luego en el login
        if (!session()->has('user')) {

            // Si NO hay sesión, redirige al login
            return redirect('/login');
        }

        // Si hay sesión, deja pasar a la ruta
        return $next($request);
    }
}