<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * Este seeder deja el sistema en el estado deseado:
         * - Admin: puede VER todos los módulos (ver)
         * - Cada rol organizacional: puede VER solo su módulo (ver)
         *
         * Nota:
         * - Usamos DB::table para evitar depender de relaciones Eloquent.
         * - Es idempotente.
         */

        // 1) Obtener ids de roles por slug
        $roles = DB::table('roles')->pluck('id', 'slug');

        // 2) Obtener permisos "ver" por módulo (module.slug)
        $verPerms = DB::table('permissions')
            ->join('modules', 'modules.id', '=', 'permissions.module_id')
            ->where('permissions.slug', 'ver')
            ->select('permissions.id as permission_id', 'modules.slug as module_slug')
            ->get();

        // Map: module_slug => permission_id
        $permByModule = $verPerms->pluck('permission_id', 'module_slug');

        // --- A) ADMIN: asignar todos los permisos "ver" de todos los módulos
        if (isset($roles['admin'])) {
            $adminRoleId = $roles['admin'];

            foreach ($permByModule as $permissionId) {
                DB::table('role_permission')->updateOrInsert(
                    ['role_id' => $adminRoleId, 'permission_id' => $permissionId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // --- B) Cada rol organizacional: asignar "ver" del módulo con el mismo slug
        foreach ($roles as $roleSlug => $roleId) {

            // Saltamos admin (ya se asignó arriba)
            if ($roleSlug === 'admin')
                continue;

            // Si existe un módulo con el mismo slug y tiene permiso ver
            if (isset($permByModule[$roleSlug])) {
                $permissionId = $permByModule[$roleSlug];

                DB::table('role_permission')->updateOrInsert(
                    ['role_id' => $roleId, 'permission_id' => $permissionId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
