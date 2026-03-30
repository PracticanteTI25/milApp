<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Servicio encargado de consultar permisos del sistema.
 *
 * IMPORTANTE:
 * - Centraliza toda la lógica de permisos.
 * - Evita duplicar queries en controladores y vistas.
 * - Facilita la migración futura a Directorio Activo.
 */
class PermissionService
{
    /**
     * Obtiene los slugs de módulos que el rol puede VER.
     *
     * @param string $roleSlug Rol técnico del sistema (ej: admin_sistema)
     * @return array Lista de slugs de módulos habilitados
     */
    public function getViewableModules(string $roleSlug): array
    {
        return DB::table('roles')
            ->join('role_permission', 'roles.id', '=', 'role_permission.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
            ->join('modules', 'modules.id', '=', 'permissions.module_id')
            ->where('roles.slug', $roleSlug)
            ->where('permissions.slug', 'ver') // permiso base para ver módulo
            ->pluck('modules.slug')
            ->unique()
            ->values()
            ->toArray();
    }
}