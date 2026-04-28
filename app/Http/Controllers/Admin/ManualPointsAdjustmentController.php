<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ManualPointsAdjustmentService;
use Illuminate\Http\Request;

class ManualPointsAdjustmentController extends Controller
{
    public function create()
    {
        return view('admin.finanzas.ajustes');
    }

    public function store(Request $request, ManualPointsAdjustmentService $service)
    {
        $data = $request->validate([
            'distributor_id' => ['required', 'integer'],
            'tipo' => ['required', 'in:positivo,negativo'],
            'puntos' => ['required', 'integer', 'min:1'],
            'estado' => ['nullable', 'in:congelado,habilitado'],
            'comentario' => ['required', 'string', 'min:5'],
        ]);

        if ($data['tipo'] === 'positivo') {
            $service->ajustar(
                distributorId: $data['distributor_id'],
                puntos: $data['puntos'],
                estadoInicial: $data['estado'],
                comentario: $data['comentario']
            );
        } else {
            $service->ajustarNegativo(
                distributorId: $data['distributor_id'],
                puntosARestar: $data['puntos'],
                comentario: $data['comentario']
            );
        }

        return redirect()->back()->with('success', 'Ajuste aplicado correctamente');
    }
}
