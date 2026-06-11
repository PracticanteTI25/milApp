<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use App\Models\PointLot;
use Carbon\Carbon;

class PointsReadService
{
    /**
     * RESUMEN GENERAL
     */
    public function resumen(int $distributorId): array
    {
        $hoy = Carbon::today();

        $bolsas = BolsaPuntos::where('distributor_id', $distributorId)->get();

        $totalDisponibles = $bolsas->sum('puntos_disponibles');

        return [
            'disponibles' => $totalDisponibles,

            // CONGELADOS DESDE KARDEX (CORRECTO)
            'congelados' => $bolsas->sum(function ($bolsa) {

                // automáticos pendientes
                $automaticosPendientes = KardexPuntos::where('bolsa_id', $bolsa->id)
                    ->where('tipo', 'generacion')
                    ->sum('puntos')

                    - KardexPuntos::where('bolsa_id', $bolsa->id)
                    ->where('tipo', 'habilitacion')
                    ->sum('puntos');

                // manual congelado (valor puro)
                $manualCongelado = KardexPuntos::where('bolsa_id', $bolsa->id)
                    ->where('impacto', 'suma_congelada')
                    ->sum('puntos');

                return max($automaticosPendientes + $manualCongelado, 0);
            }),

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
     * HISTORIAL COMPLETO DE PUNTOS
     */
    public function historial(int $distributorId): array
    {
        $bolsas = BolsaPuntos::where('distributor_id', $distributorId)
            ->orderBy('mes', 'desc')
            ->get();

        return $bolsas->map(function ($bolsa) {

            $automaticosPendientes = KardexPuntos::where('bolsa_id', $bolsa->id)
                ->where('tipo', 'generacion')
                ->sum('puntos')

                - KardexPuntos::where('bolsa_id', $bolsa->id)
                ->where('tipo', 'habilitacion')
                ->sum('puntos');

            $manualCongelado = KardexPuntos::where('bolsa_id', $bolsa->id)
                ->where('impacto', 'suma_congelada')
                ->sum('puntos');

            $congelados = max($automaticosPendientes + $manualCongelado, 0);

            // ESTADO CORRECTO
            if ($bolsa->puntos_disponibles <= 0) {
                $estado = 'Congelado';
            } elseif ($congelados <= 0) {
                $estado = 'Habilitado';
            } else {
                $estado = 'Mixto';
            }

            // VENCIMIENTOS
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
                'mes' => $bolsa->mes->format('Y-m'),
                'puntos' => $bolsa->puntos_disponibles + $congelados,
                'disponibles' => $bolsa->puntos_disponibles,

                // AQUÍ ESTÁ EL FIX REAL
                'congelados' => $congelados,

                'estado' => $estado,
                'vencimientos' => $vencimientos,
                'fecha_vencimiento' => null,

                'detalle' => KardexPuntos::where('bolsa_id', $bolsa->id)
                    ->orderBy('fecha')
                    ->get(),
            ];
        })->toArray();
    }


    /**
     * DETALLE DE CONGELADOS
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
