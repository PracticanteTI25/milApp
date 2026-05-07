<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use Carbon\Carbon;

class PointsReadService
{
    /**
     * Resumen general de puntos de una distribuidora
     */
    public function resumen(int $distributorId): array
    {
        $hoy = Carbon::today();      //obtener solo la fecha

        //filtra por distribuidor, ordena por mes y trae todo
        $bolsas = BolsaPuntos::where('distributor_id', $distributorId)
            ->orderBy('mes', 'desc')
            ->get();

        return [
            'disponibles' => $bolsas
                ->where('estado', 'habilitado')
                ->sum('puntos_disponibles'),

            'congelados' => $bolsas
                ->whereIn('estado', ['pendiente', 'congelado'])
                ->sum('puntos_disponibles'),

            'proximos_a_vencer' => $bolsas
                ->where('estado', 'habilitado')
                ->filter(function ($bolsa) use ($hoy) {    //solo alerta cuando aun no está vencido y/o faltan ≤ 30 días

                    if (!$bolsa->fecha_vencimiento) {
                        return false;
                    }

                    // Días restantes hasta vencer
                    $diasRestantes = $hoy->diffInDays($bolsa->fecha_vencimiento, false);

                    // Próximos a vencer = últimos 7 días antes del vencimiento
                    return $diasRestantes >= 0 && $diasRestantes <= 7;
                })
                ->sum('puntos_disponibles'),
        ];
    }

    /**
     * Historial completo de puntos (para tu prototipo)
     */
    public function historial(int $distributorId): array
    {
        $bolsas = BolsaPuntos::where('distributor_id', $distributorId)
            ->orderBy('mes', 'desc')
            ->get();

        return $bolsas->map(function ($bolsa) {
            return [
                'mes' => $bolsa->mes->format('Y-m'),
                'puntos' => $bolsa->puntos_generados,
                'estado' => $bolsa->estado,
                'disponibles' => $bolsa->puntos_disponibles,
                'fecha_habilitacion' => $bolsa->fecha_habilitacion,
                'fecha_vencimiento' => $bolsa->fecha_vencimiento,
                'detalle' => KardexPuntos::where('bolsa_id', $bolsa->id)
                    ->orderBy('fecha')
                    ->get(),
            ];
        })->toArray();
    }
}
