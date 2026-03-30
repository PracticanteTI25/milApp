<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Asigna TODOS los permisos al rol administrador.
     *
     * IMPORTANTE:
     * - No hay permisos implícitos.
     * - El seeder es idempotente (se puede ejecutar varias veces).
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin_sistema')->first();

        if (!$adminRole) {
            return;
        }

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
