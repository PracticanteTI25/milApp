<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        Area::firstOrCreate(
            ['slug' => 'comercial'],
            ['name' => 'Comercial', 'active' => true]
        );

        Area::firstOrCreate(
            ['slug' => 'marketing'],
            ['name' => 'Marketing', 'active' => true]
        );
    }
}
