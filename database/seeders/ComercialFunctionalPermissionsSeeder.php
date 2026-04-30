<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComercialFunctionalPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $comercial = DB::table('modules')
            ->where('slug', 'comercial')
            ->first();

        if (!$comercial) {
            return;
        }

        $permissions = [
            [
                'module_id' => $comercial->id,
                'slug' => 'registrar_distribuidoras',
                'name' => 'Registro de distribuidoras',
            ],
            [
                'module_id' => $comercial->id,
                'slug' => 'asignar_puntos',
                'name' => 'Asignación de puntos',
            ],
            [
                'module_id' => $comercial->id,
                'slug' => 'gestionar_productos',
                'name' => 'Gestión de productos',
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
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
