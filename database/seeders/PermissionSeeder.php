<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder de permisos del sistema.
 *
 * IMPORTANTE:
 * - Crea permisos CRUD para TODOS los módulos existentes.
 * - Permisos generados:
 *   - ver
 *   - crear
 *   - editar
 *   - eliminar
 * - Es IDEMPOTENTE (no duplica).
 * - Este seeder conecta:
 *   módulos -> permisos -> roles -> sidebar -> middleware
 */
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener todos los módulos activos
        $modules = DB::table('modules')
            ->where('active', true)
            ->get();

        // Acciones estándar del sistema
        $actions = [
            'ver',
            'crear',
            'editar',
            'eliminar',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {

                DB::table('permissions')->updateOrInsert(
                    [
                        // Clave única lógica
                        'module_id' => $module->id,
                        'slug' => $action,
                    ],
                    [
                        'name' => ucfirst($action) . ' ' . $module->name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
