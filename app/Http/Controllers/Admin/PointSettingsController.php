<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointSetting;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class PointSettingsController extends Controller
{
    public function edit()
    {
        return view('admin.points.settings', [
            'pointSettings' => PointSetting::current(),
            'pesosPorPunto' => BusinessSetting::getValue('pesos_por_punto', 28000),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'expiration_months' => ['required', 'integer', 'min:1', 'max:36'],
            'pesos_por_punto'   => ['required', 'numeric', 'min:1'],
        ]);

        // Actualizar vencimiento
        $pointSetting = PointSetting::current();
        $pointSetting->update([
            'expiration_months' => $data['expiration_months'],
        ]);

        // Actualizar valor económico del punto
        BusinessSetting::updateOrCreate(
            ['key' => 'pesos_por_punto'],
            ['value' => $data['pesos_por_punto']]
        );

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
