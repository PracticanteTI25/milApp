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
    public function index(Request $request, PointsReadService $pointsReadService)
    {
        $distributors = Distributor::orderBy('name')->get();

        // Inicializar SIEMPRE las variables
        $selectedDistributor = null;
        $resumen = null;
        $historial = [];
        $congeladosDetalle = [];

        // Meta (inicializada por defecto)
        $monthlyGoal = null;
        $metaMensual = 0;
        $ventasAcumuladas = 0;
        $porcentajeMeta = 0;
        $faltanteMeta = 0;

        $currentYear  = now()->year;
        $currentMonth = now()->month;

        $canjesAnio = 0;

        if ($request->filled('distributor_id')) {

            $selectedDistributor = Distributor::findOrFail($request->distributor_id);

            //  Datos de puntos (reutilizando el service)
            $resumen = $pointsReadService->resumen($selectedDistributor->id);
            $historial = $pointsReadService->historial($selectedDistributor->id);
            $congeladosDetalle = $pointsReadService->congeladosDetalle($selectedDistributor->id);

            //  Meta mensual
            $monthlyGoal = DistributorMonthlyGoal::where('distributor_id', $selectedDistributor->id)
                ->where('year', $currentYear)
                ->where('month', $currentMonth)
                ->first();

            $metaMensual = $monthlyGoal->goal_amount ?? 0;

            //  Temporal hasta API de ventas
            $ventasAcumuladas = 10650000;

            $porcentajeMeta = $metaMensual > 0
                ? round(($ventasAcumuladas / $metaMensual) * 100)
                : 0;

            $faltanteMeta = max($metaMensual - $ventasAcumuladas, 0);

            $canjesAnio = Redencion::where('distributor_id', $selectedDistributor->id)
                ->whereYear('fecha', $currentYear)
                ->count();
        }

        return view('admin.points.history', [
            'distributors'        => $distributors,
            'selectedDistributor' => $selectedDistributor,

            'resumen'             => $resumen,
            'historial'           => $historial,
            'congeladosDetalle'   => $congeladosDetalle,

            //  meta (siempre definida)
            'monthlyGoal'         => $monthlyGoal,
            'metaMensual'         => $metaMensual,
            'currentYear'         => $currentYear,
            'currentMonth'        => $currentMonth,
            'ventasAcumuladas'    => $ventasAcumuladas,
            'porcentajeMeta'      => $porcentajeMeta,
            'faltanteMeta'        => $faltanteMeta,

            // métricas adicionales
            'valorCredito'        => $resumen['disponibles'] ?? 0,
            'canjesAnio' => $canjesAnio,
        ]);
    }
}
