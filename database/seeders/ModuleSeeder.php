<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['name' => 'Usuarios', 'slug' => 'usuarios'],
            ['name' => 'Reportes', 'slug' => 'reportes'],
            ['name' => 'Marketing', 'slug' => 'marketing'],
            ['name' => 'Comercial', 'slug' => 'comercial'],
            ['name' => 'Talento Humano', 'slug' => 'talento'],
        ];

        foreach ($modules as $module) {
            DB::table('modules')->updateOrInsert(
                ['slug' => $module['slug']],
                [
                    'name' => $module['name'],
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}