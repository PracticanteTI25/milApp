<?php

namespace App\Services;

use App\Models\PointLot;
use Carbon\Carbon;

class PointsHistoryService
{
    /**
     * Historial de puntos agrupado por fecha de vencimiento
     *
     * Retorna algo como:
     * [
     *   [
     *     'fecha_vencimiento' => '2026-08-15',
     *     'puntos' => 120,
     *     'estado' => 'disponible'
     *   ],
     *   ...
     * ]
     */
    public function getExpirationSummary(int $distributorId): array
    {
        $lots = PointLot::where('distributor_id', $distributorId)
            ->whereIn('status', ['disponible', 'congelado'])
            ->where('points_remaining', '>', 0)
            ->orderBy('fecha_vencimiento')
            ->get();

        return $lots
            ->groupBy(function ($lot) {
                return $lot->fecha_vencimiento
                    ? $lot->fecha_vencimiento->format('Y-m-d')
                    : 'sin_fecha';
            })
            ->map(function ($group, $fecha) {

                return [
                    'fecha_vencimiento' => $fecha === 'sin_fecha'
                        ? null
                        : Carbon::parse($fecha),
                    'puntos' => $group->sum('points_remaining'),
                    'estado' => $group->first()->status,
                ];
            })
            ->values()
            ->toArray();
    }
}
