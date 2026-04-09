<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Directivo', 'slug' => 'directivo'],
            ['name' => 'Administrativo y financiero', 'slug' => 'administrativo_financiero'],
            ['name' => 'Investigación y desarrollo', 'slug' => 'investigacion_desarrollo'],
            ['name' => 'Talento humano', 'slug' => 'talento_humano'],
            ['name' => 'Nuevos negocios y SAC', 'slug' => 'nuevos_negocios_sac'],
            ['name' => 'Creativo', 'slug' => 'creativo'],
            ['name' => 'Marketing', 'slug' => 'marketing'],
            ['name' => 'Comercial', 'slug' => 'comercial'],
            ['name' => 'Operaciones', 'slug' => 'operaciones'],
            ['name' => 'Abastecimiento', 'slug' => 'abastecimiento'],
            ['name' => 'Calidad', 'slug' => 'calidad'],
            ['name' => 'Logística y distribución', 'slug' => 'logistica_distribucion'],
        ];

        foreach ($areas as $area) {
            DB::table('areas')->updateOrInsert(
                ['slug' => $area['slug']],
                [
                    'name' => $area['name'],
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
