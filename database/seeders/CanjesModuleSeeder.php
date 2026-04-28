<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class CanjesModuleSeeder extends Seeder
{
    public function run(): void
    {
        Module::updateOrCreate(
            ['slug' => 'canjes'],
            [
                'name' => 'Canjes',
                'icon' => 'fas fa-exchange-alt',
                'active' => true,
            ]
        );
    }
}

