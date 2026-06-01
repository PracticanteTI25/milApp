<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Devolucion;
use Illuminate\Support\Facades\Storage;


class DevolucionController extends Controller
{
    /**
     * Guardar una devolución (formulario)
     */

    public function store(Request $request)
    {
        // Validación segura (OWASP)
        $data = $request->validate([
            'lote' => ['required', 'string', 'max:255'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'imagen' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240' // 10MB
            ],
            'observaciones' => ['required', 'string', 'min:10'],
        ]);

        // Subida segura de imagen
        $path = $request->file('imagen')->store('devoluciones', 'public');

        // Guardar en BD
        Devolucion::create([
            'distributor_id' => auth('distributor')->id(),
            'lote' => $data['lote'],
            'cantidad' => $data['cantidad'],
            'imagen_path' => $path,
            'observaciones' => $data['observaciones'],
        ]);

        return back()->with('success', 'Devolución registrada correctamente.');
    }

    public function create()
    {
        // 🔹 Datos simulados (lotes temporales)
        $lotes = [
            [
                'codigo' => 'L-001',
                'nombre' => 'Producto A - Lote 001'
            ],
            [
                'codigo' => 'L-002',
                'nombre' => 'Producto B - Lote 002'
            ],
            [
                'codigo' => 'L-003',
                'nombre' => 'Producto C - Lote 003'
            ],
        ];

        return view('distribuidores.devoluciones.create', compact('lotes'));
    }
}
