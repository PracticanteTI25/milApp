<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessSetting;

class BusinessSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        //valores iniciales por defecto, de resto lee la tabla en la BD
        $settings = [
            'pesos_por_punto' => '28000',
            'meses_vigencia_puntos' => '3',
            'ajustes_manuales_habilitados' => '1',
        ];

        foreach ($settings as $key => $value) {
            BusinessSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
