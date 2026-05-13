<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use App\Services\PointsReadService;
use App\Models\DistributorMonthlyGoal;

class PointsController extends Controller
{
    public function index(PointsReadService $pointsReadService)
    {
        $distributor = auth('distributor')->user();
        $distributorId = $distributor->id;

        // DATOS DE PUNTOS (NO SE TOCA)
        $resumen = $pointsReadService->resumen($distributorId);
        $historial = $pointsReadService->historial($distributorId);
        $congeladosDetalle = $pointsReadService->congeladosDetalle($distributorId);

        // PERIODO ACTUAL
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        // META DESDE BD (reemplaza el hardcodeo)
        $monthlyGoal = DistributorMonthlyGoal::where('distributor_id', $distributorId)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->first();

        // Si no hay meta, evitar errores
        $metaMensual = $monthlyGoal->goal_amount ?? 0;

        // Esto sigue siendo temporal hasta conectar ventas reales
        $ventasAcumuladas = 10650000;

        $porcentajeMeta = $metaMensual > 0
            ? round(($ventasAcumuladas / $metaMensual) * 100)
            : 0;

        $faltanteMeta = max($metaMensual - $ventasAcumuladas, 0);

        return view('distribuidores.puntos.index', [
            // puntos (igual que antes)
            'resumen' => $resumen,
            'historial' => $historial,
            'congeladosDetalle' => $congeladosDetalle,

            // meta real
            'metaMensual' => $metaMensual,
            'monthlyGoal' => $monthlyGoal,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,

            // métricas calculadas
            'ventasAcumuladas' => $ventasAcumuladas,
            'porcentajeMeta' => $porcentajeMeta,
            'faltanteMeta' => $faltanteMeta,

            // métricas adicionales (igual que antes)
            'canjesAnio' => 7,
            'valorCredito' => $resumen['disponibles'],
        ]);
    }
}
