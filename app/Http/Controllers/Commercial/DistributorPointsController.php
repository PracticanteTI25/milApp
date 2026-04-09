<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Services\PointsService;
use Illuminate\Http\Request;

class DistributorPointsController extends Controller
{
    /**
     * Listado de distribuidoras para asignar puntos.
     */
    public function index()
    {
        // Traemos las distribuidoras con su saldo y puntos redimidos
        $distributors = Distributor::query()
            ->select('id', 'name', 'email', 'points_balance', 'points_redeemed', 'active')
            ->orderBy('name')
            ->get();

        return view('areas.comercial.puntos.index', compact('distributors'));
    }

    /**
     * Actualiza puntos de UNA distribuidora (sumar o restar).
     */
    public function update(Request $request, PointsService $pointsService, $id)
    {
        // Validaciones de seguridad (evita datos inválidos)
        $data = $request->validate([
            'operation' => ['required', 'in:plus,minus'], // plus o minus
            'amount' => ['required', 'integer', 'min:1'],
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            if ($data['operation'] === 'plus') {
                //Sumar puntos manualmente
                $pointsService->manualCredit(
                    (int) $id,
                    (int) $data['amount'],
                    $data['comment'] ?? null,
                    auth()->id()
                );
            } else {
                //Restar puntos manualmente (corrección)
                $pointsService->manualDebit(
                    (int) $id,
                    (int) $data['amount'],
                    $data['comment'] ?? null,
                    auth()->id()
                );
            }

            return redirect()
                ->route('comercial.puntos.index')
                ->with('success', 'Puntos actualizados correctamente.');

        } catch (\Throwable $e) {
            // Mensaje amigable (sin mostrar detalles técnicos)
            return back()->withErrors([
                'points' => $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Muestra el historial (extracto) de puntos de una distribuidora.
     */
    public function history($id)
    {
        // Traemos la distribuidora
        $distributor = Distributor::findOrFail($id);

        // Traemos su historial de movimientos (más reciente primero)
        $movements = $distributor->pointMovements()
            ->with('distributor')
            ->orderByDesc('created_at')
            ->paginate(20); // paginación para no saturar la vista

        return view('areas.comercial.puntos.historial', compact('distributor', 'movements'));
    }
}