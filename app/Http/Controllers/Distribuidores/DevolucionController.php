<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    /**
     * Guardar una devolución (formulario)
     */
    public function store(Request $request)
    {
        // Validación (OWASP: evita datos incorrectos o maliciosos)
        $data = $request->validate([
            'lote' => ['required', 'string'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'imagen' => ['required', 'image', 'max:10240'], // max 10MB
            'observaciones' => ['required', 'string', 'min:10'],
        ]);

        // Por ahora solo simulamos (no BD aún)
        // Aquí luego conectamos:
        // - DB
        // - API
        // - Storage

        return back()->with('success', 'Solicitud de devolución enviada correctamente.');
    }
}
