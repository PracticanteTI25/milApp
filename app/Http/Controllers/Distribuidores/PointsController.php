<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use App\Services\PointsReadService;
use App\Models\DistributorMonthlyGoal;
use App\Models\Redencion;
use App\Models\Sale;

class PointsController extends Controller
{
    public function index(PointsReadService $pointsReadService)
    {
        $distributor = auth('distributor')->user();
        $distributorId = $distributor->id;

        // PUNTOS (NO SE TOCA)
        $resumen = $pointsReadService->resumen($distributorId);
        $historial = $pointsReadService->historial($distributorId);
        $congeladosDetalle = $pointsReadService->congeladosDetalle($distributorId);

        // PERIODO ACTUAL
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        // META
        $monthlyGoal = DistributorMonthlyGoal::with('sale')
            ->where('distributor_id', $distributorId)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->first();

        $metaMensual = $monthlyGoal->goal_amount ?? 0;

        // VENTAS REALES (DESDE BD)
        $ventasAcumuladas = $monthlyGoal->sale->achieved_amount ?? 0;

        // CÁLCULO DE CUMPLIMIENTO
        $porcentajeMeta = $metaMensual > 0
            ? min(100, round(($ventasAcumuladas / $metaMensual) * 100))
            : 0;

        // resultado
        $faltanteMeta = max($metaMensual - $ventasAcumuladas, 0);

        // MÉTRICAS ADICIONALES
        $canjesAnio = Redencion::where('distributor_id', $distributorId)
            ->whereYear('fecha', $currentYear)
            ->count();

        return view('distribuidores.puntos.index', [
            // puntos
            'resumen' => $resumen,
            'historial' => $historial,
            'congeladosDetalle' => $congeladosDetalle,

            // meta
            'metaMensual' => $metaMensual,
            'monthlyGoal' => $monthlyGoal,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,

            // cálculo real (compatibles con la vista)
            'achieved' => $ventasAcumuladas,
            'percentage' => $porcentajeMeta,
            'goal' => $monthlyGoal,

            // métricas adicionales
            'canjesAnio' => $canjesAnio,
            'valorCredito' => $resumen['disponibles'],
        ]);
    }
}
