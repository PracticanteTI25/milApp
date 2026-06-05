<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use App\Services\PointsReadService;
use App\Models\DistributorMonthlyGoal;
use App\Models\Redencion;

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

        // META + VENTAS 
        $monthlyGoal = DistributorMonthlyGoal::with([
            'sales' => function ($query) use ($currentYear, $currentMonth) {
                $query->where('year', $currentYear)
                    ->where('month', $currentMonth);
            }
        ])
            ->where('distributor_id', $distributorId)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->first();

        // Meta
        $metaMensual = $monthlyGoal->goal_amount ?? 0;

        // Venta segura (evita null)
        $sale = ($monthlyGoal && $monthlyGoal->sales->isNotEmpty())
            ? $monthlyGoal->sales->first()
            : null;

        $ventasAcumuladas = $sale ? $sale->achieved_amount : 0;

        // CÁLCULO DE CUMPLIMIENTO
        $porcentajeMeta = $metaMensual > 0
            ? min(100, round(($ventasAcumuladas / $metaMensual) * 100))
            : 0;

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

            // datos finales ya listos para la vista
            'achieved' => $ventasAcumuladas,
            'percentage' => $porcentajeMeta,
            'goal' => $monthlyGoal,
            'faltanteMeta' => $faltanteMeta,

            // otras métricas
            'canjesAnio' => $canjesAnio,
            'valorCredito' => $resumen['disponibles'],
        ]);
    }
}
