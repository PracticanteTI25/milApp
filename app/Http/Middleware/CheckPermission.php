<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware CheckPermission
 *
 * Valida si el usuario autenticado tiene un permiso específico.
 *
 * Uso en rutas:
 *   ->middleware('permission:finanzas.ajustes')
 *
 * Seguridad:
 * - Control de acceso REAL (OWASP A01: Broken Access Control)
 * - Soporta permisos por usuario y por rol
 * - El frontend NO decide permisos
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permissionSlug)
    {
        /*
        |--------------------------------------------------------------
        | 1. Usuario autenticado
        |--------------------------------------------------------------
        */
        $user = auth()->user();

        if (!$user) {
            abort(401, 'No autenticado');
        }

        /*
        |--------------------------------------------------------------
        | 2. Obtener TODOS los permisos efectivos del usuario
        |--------------------------------------------------------------
        | Incluye:
        | - Permisos directos (user_permission)
        | - Permisos heredados del rol (role_permission)
        */
        $permissions = $user->allPermissions();

        /*
        |--------------------------------------------------------------
        | 3. Validar permiso
        |--------------------------------------------------------------
        */
        if (!$permissions->contains('slug', $permissionSlug)) {
            abort(403, 'No autorizado');
        }

        /*
        |--------------------------------------------------------------
        | 4. Permiso válido → continuar
        |--------------------------------------------------------------
        */
        return $next($request);
    }
}
