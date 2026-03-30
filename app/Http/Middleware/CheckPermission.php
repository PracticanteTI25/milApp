<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Middleware CheckPermission
 *
 * Valida si el usuario autenticado tiene permiso
 * para acceder a un módulo y acción específicos.
 *
 * Ejemplo de uso en rutas:
 *   ->middleware('permission:reportes.ver')
 *
 * Seguridad:
 * - Controla acceso REAL (OWASP A01: Broken Access Control)
 * - El frontend NO decide permisos
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Verificar que haya un usuario autenticado
        |--------------------------------------------------------------------------
        */
        $user = auth()->user();

        if (!$user) {
            abort(401, 'No autenticado');
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Verificar que el usuario tenga un rol asignado
        |--------------------------------------------------------------------------
        */
        if (!$user->role) {
            abort(403, 'Usuario sin rol asignado');
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Separar el permiso solicitado
        |--------------------------------------------------------------------------
        | Formato esperado: modulo.accion
        | Ejemplo: reportes.ver
        */
        if (!str_contains($permission, '.')) {
            abort(500, 'Formato de permiso inválido');
        }

        [$moduleSlug, $actionSlug] = explode('.', $permission);

        /*
        |--------------------------------------------------------------------------
        | 4. Consultar en base de datos si el rol tiene el permiso
        |--------------------------------------------------------------------------
        | Relaciones:
        | roles -> role_permission -> permissions -> modules
        */
        $hasPermission = DB::table('roles')
            ->join('role_permission', 'roles.id', '=', 'role_permission.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
            ->join('modules', 'modules.id', '=', 'permissions.module_id')
            ->where('roles.id', $user->role_id)
            ->where('modules.slug', $moduleSlug)
            ->where('permissions.slug', $actionSlug)
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | 5. Bloquear acceso si no tiene permiso
        |--------------------------------------------------------------------------
        */
        if (!$hasPermission) {
            abort(403, 'No autorizado');
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Permiso válido → continuar flujo normal
        |--------------------------------------------------------------------------
        */
        return $next($request);
    }
}
