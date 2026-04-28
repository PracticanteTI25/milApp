<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessSetting;

class PointsService
{
    /**
     * Regla base: pesos por punto
     * (Luego esto saldrá de configuración)
     */

    private function pesosPorPunto(): int
    {
        return (int) BusinessSetting::where('key', 'pesos_por_punto')->value('value');
    }


    /**
     * Procesa una venta y suma puntos
     */
    public function procesarVenta(int $distributorId, Carbon $fecha, float $monto): void
    {
        DB::transaction(function () use ($distributorId, $fecha, $monto) {

            // Calcular puntos (entero)
            $puntos = intdiv((int) $monto, $this->pesosPorPunto());

            if ($puntos <= 0) {
                return; // venta sin puntos
            }

            //  Mes de la bolsa (YYYY-MM-01)
            $mesBolsa = $fecha->copy()->startOfMonth();

            //  Obtener o crear bolsa
            $bolsa = BolsaPuntos::firstOrCreate(
                [
                    'distributor_id' => $distributorId,
                    'mes' => $mesBolsa,
                ],
                [
                    'puntos_generados' => 0,
                    'puntos_disponibles' => 0,
                    'estado' => 'pendiente',
                ]
            );

            //  Sumar puntos
            $bolsa->increment('puntos_generados', $puntos);
            $bolsa->increment('puntos_disponibles', $puntos);

            //  Kardex
            KardexPuntos::create([
                'distributor_id' => $distributorId,
                'bolsa_id' => $bolsa->id,
                'tipo' => 'generacion',
                'puntos' => $puntos,
                'descripcion' => 'Generación automática por venta',
                'fecha' => $fecha,
            ]);
        });
    }
}
