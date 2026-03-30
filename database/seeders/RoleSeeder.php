<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(
            ['slug' => 'admin_sistema'],
            ['name' => 'Administrador del sistema', 'active' => true]
        );

        Role::firstOrCreate(
            ['slug' => 'consulta'],
            ['name' => 'Consulta', 'active' => true]
        );
    }
}
