<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ACCESO A ÁREAS SEGÚN PERMISOS
 * Controlador genérico para áreas.
 * Luego cada área tendrá su módulo completo.
 */
class AreaController extends Controller
{
    public function show(string $slug)
    {
        $user = auth()->user();

        if (!$user || !$user->role_id) {
            abort(401);
        }

        // Validar permiso requerido: {slug}.ver
        $hasPermission = DB::table('role_permission')
            ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
            ->join('modules', 'modules.id', '=', 'permissions.module_id')
            ->where('role_permission.role_id', $user->role_id)
            ->where('modules.slug', $slug)
            ->where('permissions.slug', 'ver')
            ->exists();

        if (!$hasPermission) {
            abort(403, 'No autorizado');
        }

        return view('areas.show', [
            'slug' => $slug,
        ]);
    }
}
