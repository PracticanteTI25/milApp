<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Obtener el módulo Comercial
        $comercialModule = DB::table('modules')
            ->where('slug', 'comercial')
            ->first();

        if (!$comercialModule) {
            return; // Seguridad: no hacemos nada si no existe
        }

        $permissions = [
            [
                'module_id' => $comercialModule->id,
                'name' => 'Registro de distribuidoras',
                'slug' => 'registrar_distribuidoras',
            ],
            [
                'module_id' => $comercialModule->id,
                'name' => 'Asignación de puntos',
                'slug' => 'asignar_puntos',
            ],
            [
                'module_id' => $comercialModule->id,
                'name' => 'Gestión de productos',
                'slug' => 'gestionar_productos',
            ],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                [
                    'module_id' => $permission['module_id'],
                    'slug' => $permission['slug'],
                ],
                [
                    'name' => $permission['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        $comercialModule = DB::table('modules')
            ->where('slug', 'comercial')
            ->first();

        if (!$comercialModule) {
            return;
        }

        DB::table('permissions')
            ->where('module_id', $comercialModule->id)
            ->whereIn('slug', [
                'registrar_distribuidoras',
                'asignar_puntos',
                'gestionar_productos',
            ])
            ->delete();
    }
};