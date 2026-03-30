<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = DB::table('modules')->get();

        $actions = ['ver', 'crear', 'editar', 'eliminar'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                DB::table('permissions')->updateOrInsert(
                    [
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
