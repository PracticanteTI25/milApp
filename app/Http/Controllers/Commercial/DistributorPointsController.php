<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Services\PermissionRegistry;
use App\Services\ManualPointsAdjustmentService;
use Illuminate\Http\Request;

class DistributorPointsController extends Controller
{

    /**
     * Listado de distribuidoras para asignar puntos
     */
    public function index()
    {
        // Traemos solo información básica necesaria
        $distributors = Distributor::query()
            ->select('id', 'name', 'email', 'active')
            ->orderBy('name')
            ->get();

        return view('areas.comercial.puntos.index', compact('distributors'));
    }

    /**
     * Aplica un ajuste manual de puntos (positivo o negativo)
     */
    public function update(
        Request $request,
        ManualPointsAdjustmentService $pointsService,
        int $id
    ) {
        $data = $request->validate([
            'operation' => ['required', 'in:plus,minus'],
            'amount'    => ['required', 'integer', 'min:1'],
            'comment'   => ['required', 'string', 'min:5'],
        ]);

        try {
            if ($data['operation'] === 'plus') {
                // Ajuste manual POSITIVO
                $pointsService->ajustar(
                    distributorId: $id,
                    puntos: $data['amount'],
                    estadoInicial: 'habilitado',
                    comentario: $data['comment']
                );
            } else {
                // Ajuste manual NEGATIVO (FIFO)
                $pointsService->ajustarNegativo(
                    distributorId: $id,
                    puntosARestar: $data['amount'],
                    comentario: $data['comment']
                );
            }

            return redirect()
                ->route('comercial.puntos.index')
                ->with('success', 'Puntos actualizados correctamente.');
        } catch (\Throwable $e) {
            return back()
                ->withErrors([
                    'points' => $e->getMessage(),
                ])
                ->withInput();
        }
    }

    /**
     * Historial de movimientos de puntos de una distribuidora
     */
    public function history(int $id)
    {
        $distributor = Distributor::findOrFail($id);

        $movements = $distributor->pointMovements()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view(
            'areas.comercial.puntos.historial',
            compact('distributor', 'movements')
        );
    }
}
