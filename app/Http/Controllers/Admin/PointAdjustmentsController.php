<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Services\PointAdjustmentService;
use Illuminate\Http\Request;

class PointAdjustmentsController extends Controller
{
    public function index()
    {
        $distributors = Distributor::orderBy('name')->get();

        return view('admin.points.adjustments', compact('distributors'));
    }

    public function store(Request $request, PointAdjustmentService $service)
    {
        $data = $request->validate([
            'distributor_id' => ['required', 'exists:distributors,id'],
            'type' => ['required', 'in:add,subtract'],
            'points' => ['required', 'integer', 'min:1'],
            'initial_state' => ['nullable', 'in:habilitado,congelado'],
            'reason' => ['required', 'string', 'min:5'],
        ]);

        try {

            if ($data['type'] === 'add') {

                $service->addPoints(
                    $data['distributor_id'],
                    $data['points'],
                    $data['initial_state'],
                    $data['reason']
                );
            } else {

                $service->subtractPoints(
                    $data['distributor_id'],
                    $data['points'],
                    $data['reason']
                );
            }

            return back()->with('success', 'Ajuste aplicado correctamente.');
        } catch (\RuntimeException $e) {

            // Mensaje de negocio (esperado)
            return back()
                ->withErrors([
                    'points' => $e->getMessage(),
                ])
                ->withInput();
        } catch (\Throwable $e) {

            // Cualquier otro error inesperado
            report($e);

            return back()
                ->withErrors([
                    'general' => 'Ocurrió un error inesperado. Intenta nuevamente o contacta soporte.',
                ])
                ->withInput();
        }
    }
}
