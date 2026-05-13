<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointSetting;
use Illuminate\Http\Request;

class PointSettingsController extends Controller
{
    /**
     * Mostrar configuración actual
     */
    public function edit()
    {
        $settings = PointSetting::current();   //obtiene la configuracion actual

        return view('admin.points.settings', compact('settings'));   //envia los datos a la vista
    }

    //Guarda el formulario (la configuracion)
    public function update(Request $request)
    {
        // Validación (OWASP: input validation)
        $data = $request->validate([
            'expiration_months' => ['required', 'integer', 'min:1', 'max:36'],
        ]);

        $settings = PointSetting::current();   //trabajamos sobre la misma cofiguracion, la que obtuvimos
        $settings->update($data);   //actualiza la configuracion 

        return redirect()
            ->back()
            ->with('success', 'La configuración de vencimiento fue actualizada correctamente.');
    }
}
