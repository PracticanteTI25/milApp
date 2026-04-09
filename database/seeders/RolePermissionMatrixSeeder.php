<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Module;
use App\Models\Permission;

class RolePermissionMatrixSeeder extends Seeder
{
    public function run(): void
    {
        $matrix = [
            'admin' => Module::pluck('slug')->toArray(),

            'directivo' => ['directivo'],
            'administrativo_financiero' => ['administrativo_financiero'],
            'investigacion_desarrollo' => ['investigacion_desarrollo'],
            'talento_humano' => ['talento_humano'],
            'nuevos_negocios_sac' => ['nuevos_negocios_sac'],
            'creativo' => ['creativo'],
            'marketing' => ['marketing'],
            'comercial' => ['comercial'],
            'operaciones' => ['operaciones'],
            'abastecimiento' => ['abastecimiento'],
            'calidad' => ['calidad'],
            'logistica_distribucion' => ['logistica_distribucion'],
        ];

        foreach ($matrix as $roleSlug => $moduleSlugs) {

            $role = Role::where('slug', $roleSlug)->first();
            if (!$role)
                continue;

            $permissionIds = Permission::where('slug', 'ver')
                ->whereHas('module', fn($q) => $q->whereIn('slug', $moduleSlugs))
                ->pluck('id');

            $role->permissions()->sync($permissionIds);
        }
    }
}
