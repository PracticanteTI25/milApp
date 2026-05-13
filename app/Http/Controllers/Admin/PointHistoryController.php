<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Services\PointsReadService;
use Illuminate\Http\Request;
use App\Models\DistributorMonthlyGoal;

class PointHistoryController extends Controller
{
    public function index(Request $request, PointsReadService $pointsReadService)
    {
        // Selector de distribuidoras
        $distributors = Distributor::orderBy('name')->get();

        $selectedDistributor = null;
        $resumen = null;
        $historial = [];
        $congeladosDetalle = [];

        if ($request->filled('distributor_id')) {
            $selectedDistributor = Distributor::findOrFail($request->distributor_id);

            // MISMA LÓGICA QUE VE LA DISTRIBUIDORA
            $resumen = $pointsReadService->resumen($selectedDistributor->id);
            $historial = $pointsReadService->historial($selectedDistributor->id);
            $congeladosDetalle = $pointsReadService->congeladosDetalle($selectedDistributor->id);

            $currentYear  = now()->year;
            $currentMonth = now()->month;

            $monthlyGoal = DistributorMonthlyGoal::where('distributor_id', $selectedDistributor->id)
                ->where('year', $currentYear)
                ->where('month', $currentMonth)
                ->first();

            $metaMensual = $monthlyGoal->goal_amount ?? 0;

            // Simulado hasta API real (igual que distribuidora)
            $ventasAcumuladas = 10650000;

            $porcentajeMeta = $metaMensual > 0
                ? round(($ventasAcumuladas / $metaMensual) * 100)
                : 0;

            $faltanteMeta = max($metaMensual - $ventasAcumuladas, 0);
        }

        return view('admin.points.history', [
            'distributors'        => $distributors,
            'selectedDistributor' => $selectedDistributor,

            'resumen'             => $resumen,
            'historial'           => $historial,
            'congeladosDetalle'   => $congeladosDetalle,

            // meta
            'monthlyGoal'         => $monthlyGoal,
            'metaMensual'         => $metaMensual,
            'currentYear'         => $currentYear,
            'currentMonth'        => $currentMonth,
            'ventasAcumuladas'    => $ventasAcumuladas,
            'porcentajeMeta'      => $porcentajeMeta,
            'faltanteMeta'        => $faltanteMeta,

            // métricas
            'valorCredito'        => $resumen['disponibles'],
            'canjesAnio'          => 7,
        ]);
    }
}
