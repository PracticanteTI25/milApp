<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Services\PointsReadService;
use Illuminate\Http\Request;
use App\Models\DistributorMonthlyGoal;
use App\Models\Redencion;

class PointHistoryController extends Controller
{
    public function index(
        Request $request,
        PointsReadService $pointsReadService
    ) {
        $distributors = Distributor::orderBy('name')->get();

        // Inicialización
        $selectedDistributor = null;

        $resumen = null;
        $historial = [];
        $congeladosDetalle = [];

        $monthlyGoal = null;
        $metaMensual = 0;
        $ventasAcumuladas = 0;
        $porcentajeMeta = 0;
        $faltanteMeta = 0;

        $currentYear  = now()->year;
        $currentMonth = now()->month;

        $canjesAnio = 0;

        // Cuando seleccionan distribuidor
        if ($request->filled('distributor_id')) {

            $selectedDistributor = Distributor::findOrFail($request->distributor_id);

            // FUENTE DE VERDAD (PUNTOS)
            $resumen = $pointsReadService->resumen($selectedDistributor->id);
            $historial = $pointsReadService->historial($selectedDistributor->id);
            $congeladosDetalle = $pointsReadService->congeladosDetalle($selectedDistributor->id);

            // META + VENTAS (CORRECTO)
            $monthlyGoal = DistributorMonthlyGoal::with([
                'sales' => function ($query) use ($currentYear, $currentMonth) {
                    $query->where('year', $currentYear)
                        ->where('month', $currentMonth);
                }
            ])
                ->where('distributor_id', $selectedDistributor->id)
                ->where('year', $currentYear)
                ->where('month', $currentMonth)
                ->first();

            $metaMensual = $monthlyGoal->goal_amount ?? 0;

            // Obtener venta correcta
            $sale = ($monthlyGoal && $monthlyGoal->sales->isNotEmpty())
                ? $monthlyGoal->sales->first()
                : null;

            $ventasAcumuladas = $sale ? $sale->achieved_amount : 0;

            // Cálculo 
            $porcentajeMeta = $metaMensual > 0
                ? min(100, round(($ventasAcumuladas / $metaMensual) * 100))
                : 0;

            $faltanteMeta = max($metaMensual - $ventasAcumuladas, 0);

            // Canjes del año
            $canjesAnio = Redencion::where('distributor_id', $selectedDistributor->id)
                ->whereYear('fecha', $currentYear)
                ->count();
        }

        // Vista
        return view('admin.points.history', [
            'distributors'        => $distributors,
            'selectedDistributor' => $selectedDistributor,

            'resumen'             => $resumen,
            'historial'           => $historial,
            'congeladosDetalle'   => $congeladosDetalle,

            'monthlyGoal'         => $monthlyGoal,
            'metaMensual'         => $metaMensual,
            'currentYear'         => $currentYear,
            'currentMonth'        => $currentMonth,
            'ventasAcumuladas'    => $ventasAcumuladas,
            'porcentajeMeta'      => $porcentajeMeta,
            'faltanteMeta'        => $faltanteMeta,

            'valorCredito'        => $resumen['disponibles'] ?? 0,
            'canjesAnio'          => $canjesAnio,
        ]);
    }
}
