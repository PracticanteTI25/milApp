<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder de módulos del sistema.
 *
 * IMPORTANTE:
 * - Un módulo representa un punto funcional del sistema
 * - Incluye módulos técnicos (Usuarios, Reportes)
 * - Incluye áreas organizacionales (Directivo, Comercial, etc.)
 * - Este seeder es IDEMPOTENTE (se puede ejecutar varias veces)
 */
class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [

            // ======================
            // MÓDULOS TÉCNICOS
            // ======================
            [
                'name' => 'Usuarios',
                'slug' => 'usuarios',
                'icon' => 'fas fa-users',
            ],
            [
                'name' => 'Reportes',
                'slug' => 'reportes',
                'icon' => 'fas fa-chart-bar',
            ],

            // ======================
            // ÁREAS ORGANIZACIONALES
            // ======================
            [
                'name' => 'Directivo',
                'slug' => 'directivo',
                'icon' => 'fas fa-user-tie',
            ],
            [
                'name' => 'Administrativo y financiero',
                'slug' => 'administrativo_financiero',
                'icon' => 'fas fa-file-invoice-dollar',
            ],
            [
                'name' => 'Investigación y desarrollo',
                'slug' => 'investigacion_desarrollo',
                'icon' => 'fas fa-flask',
            ],
            [
                'name' => 'Talento humano',
                'slug' => 'talento_humano',
                'icon' => 'fas fa-users-cog',
            ],
            [
                'name' => 'Nuevos negocios y SAC',
                'slug' => 'negocios_sac',
                'icon' => 'fas fa-handshake',
            ],
            [
                'name' => 'Creativo',
                'slug' => 'creativo',
                'icon' => 'fas fa-palette',
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'icon' => 'fas fa-bullhorn',
            ],
            [
                'name' => 'Comercial',
                'slug' => 'comercial',
                'icon' => 'fas fa-briefcase',
            ],
            [
                'name' => 'Operaciones',
                'slug' => 'operaciones',
                'icon' => 'fas fa-cogs',
            ],
            [
                'name' => 'Abastecimiento',
                'slug' => 'abastecimiento',
                'icon' => 'fas fa-boxes',
            ],
            [
                'name' => 'Calidad',
                'slug' => 'calidad',
                'icon' => 'fas fa-check-circle',
            ],
            [
                'name' => 'Logística y distribución',
                'slug' => 'logistica_distribucion',
                'icon' => 'fas fa-truck',
            ],
        ];

        foreach ($modules as $module) {
            DB::table('modules')->updateOrInsert(
                ['slug' => $module['slug']],
                [
                    'name' => $module['name'],
                    'icon' => $module['icon'],
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
