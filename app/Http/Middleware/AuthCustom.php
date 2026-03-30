<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCustom
{
    /**
     * Middleware de autenticación basado en sesión "user".
     *
     * Se asegura de que exista un usuario en sesión antes de permitir
     * el acceso a rutas protegidas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionUser = session('user');

        // Validamos que exista y sea un array (estructura esperada)
        if (!$sessionUser || !is_array($sessionUser)) {

            // Si la petición espera JSON (por ejemplo, AJAX),
            // devolvemos un 401 en formato JSON.
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No autenticado.',
                ], 401);
            }

            // Para peticiones normales, redirigimos a la ruta del login
            return redirect()->route('login');
        }

        // Si hay sesión válida, dejamos pasar a la ruta
        return $next($request);
    }
}