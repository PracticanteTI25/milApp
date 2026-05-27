<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use App\Models\PointLot;
use Carbon\Carbon;

class PointsReadService
{
    /**
     * Resumen general de puntos de una distribuidora
     */
    public function resumen(int $distributorId): array
    {
        $hoy = Carbon::today(); // solo fecha, sin hora

        // Traer TODAS las bolsas del distribuidor
        $bolsas = BolsaPuntos::where('distributor_id', $distributorId)->get();

        $totalGenerados   = $bolsas->sum('puntos_generados');
        $totalDisponibles = $bolsas->sum('puntos_disponibles');

        return [
            // DISPONIBLES: se leen DIRECTAMENTE del contador
            'disponibles' => $totalDisponibles,

            // CONGELADOS: diferencia contable (NO por estado)
            'congelados' => max($totalGenerados - $totalDisponibles, 0),

            // PRÓXIMOS A VENCER (se mantiene igual por ahora)
            'proximos_a_vencer' => $bolsas
                ->filter(function ($bolsa) use ($hoy) {

                    if (!$bolsa->fecha_vencimiento) {
                        return false;
                    }

                    $diasRestantes = $hoy->diffInDays($bolsa->fecha_vencimiento, false);

                    return $diasRestantes >= 0
                        && $diasRestantes <= 7
                        && $bolsa->puntos_disponibles > 0;
                })
                ->sum('puntos_disponibles'),
        ];
    }

    /**
     * Historial completo de puntos
     * (estructura ORIGINAL, vencimientos corregidos)
     */
    public function historial(int $distributorId): array
    {
        $bolsas = BolsaPuntos::where('distributor_id', $distributorId)
            ->orderBy('mes', 'desc')
            ->get();

        return $bolsas->map(function ($bolsa) {

            // ============================
            // ESTADO DERIVADO (SIN CAMBIOS)
            // ============================
            if ($bolsa->puntos_disponibles <= 0) {
                $estado = 'Congelado';
            } elseif ($bolsa->puntos_disponibles >= $bolsa->puntos_generados) {
                $estado = 'Habilitado';
            } else {
                $estado = 'Mixto';
            }

            // =================================================
            // VENCIMIENTOS REALES DESDE POINT_LOTS
            // =================================================
            $vencimientos = PointLot::where('bolsa_id', $bolsa->id)
                ->where('status', 'disponible')
                ->where('points_remaining', '>', 0)
                ->whereNotNull('fecha_vencimiento')
                ->get()
                ->groupBy(fn($lot) => $lot->fecha_vencimiento->format('Y-m-d'))
                ->map(function ($lots, $fecha) {
                    $totalPuntos = $lots->sum('points_remaining');

                    return Carbon::parse($fecha)->format('d/m/Y') . " ({$totalPuntos} pts)";
                })
                ->values()
                ->toArray();


            return [
                // ESTRUCTURA ORIGINAL (NO SE TOCA)
                'mes' => $bolsa->mes->format('Y-m'),
                'puntos' => $bolsa->puntos_generados,
                'disponibles' => $bolsa->puntos_disponibles,
                'congelados' => $bolsa->puntos_generados - $bolsa->puntos_disponibles,
                'estado' => $estado,

                'vencimientos' => $vencimientos,

                // Se deja por compatibilidad
                'fecha_vencimiento' => null,

                // Detalle ORIGINAL (kardex)
                'detalle' => KardexPuntos::where('bolsa_id', $bolsa->id)
                    ->orderBy('fecha')
                    ->get(),
            ];
        })->toArray();
    }

    /**
     * Detalle de puntos congelados (tooltip / panel)
     * (SIN CAMBIOS)
     */
    public function congeladosDetalle(int $distributorId): array
    {
        return BolsaPuntos::where('distributor_id', $distributorId)
            ->where('estado', 'congelado')
            ->orderBy('mes')
            ->get()
            ->map(function ($bolsa) {
                return [
                    'mes' => $bolsa->mes->format('M Y'),
                    'puntos' => $bolsa->puntos_generados,
                    'vencen' => optional($bolsa->fecha_vencimiento)?->format('M Y'),
                    'motivo' => 'Meta mensual no alcanzada en ' . $bolsa->mes->translatedFormat('F'),
                ];
            })
            ->toArray();
    }
}
