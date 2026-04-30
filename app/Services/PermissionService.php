<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class PermissionService
{
    /**
     * NUEVO MÉTODO (PRINCIPAL)
     * Obtiene los módulos visibles para un usuario,
     * considerando ROLES MÚLTIPLES.
     */
    public function getViewableModulesForUser(User $user): array
    {
        //  Obtener roles desde la relación nueva
        $roleIds = $user->roles()->pluck('roles.id');

        // Fallback: si aún no tiene roles múltiples,
        // usamos el role_id legacy para NO romper nada
        if ($roleIds->isEmpty() && $user->role_id) {
            $roleIds = collect([$user->role_id]);
        }

        if ($roleIds->isEmpty()) {
            return [];
        }

        return DB::table('role_permission')
            ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
            ->join('modules', 'modules.id', '=', 'permissions.module_id')
            ->whereIn('role_permission.role_id', $roleIds)
            ->where('permissions.slug', 'ver')
            ->pluck('modules.slug')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     *  MÉTODO LEGACY (NO TOCAR AÚN)
     * Se mantiene para compatibilidad temporal.
     */
    public function getViewableModules(string $roleSlug): array
    {
        return DB::table('roles')
            ->join('role_permission', 'roles.id', '=', 'role_permission.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
            ->join('modules', 'modules.id', '=', 'permissions.module_id')
            ->where('roles.slug', $roleSlug)
            ->where('permissions.slug', 'ver')
            ->pluck('modules.slug')
            ->unique()
            ->values()
            ->toArray();
    }
}