<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Obtener usuario de sesión
        $user = session('user');

        // Validar que exista y sea array
        if (!$user || !is_array($user)) {
            return redirect('/login');
        }

        // Validar que tenga rol
        if (!isset($user['rol'])) {
            abort(403, 'Rol no definido');
        }

        // Validar permisos
        if (!in_array($user['rol'], $roles)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}