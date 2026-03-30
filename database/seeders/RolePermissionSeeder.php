<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

/**
 * Asigna permisos al rol administrador.
 *
 * IMPORTANTE:
 * - Este seeder ES IDEMPOTENTE
 * - Se puede ejecutar varias veces sin romper datos
 * - NO duplica registros
 * - NO asume que la base está vacía
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener rol administrador
        $adminRole = Role::where('slug', 'admin_sistema')->first();

        // Si no existe el rol, no hacemos nada
        if (!$adminRole) {
            return;
        }

        // Obtener todos los permisos existentes
        $permissions = DB::table('permissions')->pluck('id');

        foreach ($permissions as $permissionId) {
            DB::table('role_permission')->updateOrInsert(
                [
                    'role_id' => $adminRole->id,
                    'permission_id' => $permissionId,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}