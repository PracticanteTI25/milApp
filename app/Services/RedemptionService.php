<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use App\Models\Redencion;
use App\Models\RedencionDetalle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RedemptionService
{
    public function redimir(
        int $distributorId,
        int $direccionId,
        int $puntosSolicitados
    ): void {
        DB::transaction(function () use ($distributorId, $direccionId, $puntosSolicitados) {

            //  Bolsas habilitadas FIFO
            $bolsas = BolsaPuntos::where('distributor_id', $distributorId)
                ->where('estado', 'habilitado')
                ->where('puntos_disponibles', '>', 0)
                ->orderBy('fecha_habilitacion')
                ->lockForUpdate()
                ->get();

            $restantes = $puntosSolicitados;

            if ($bolsas->sum('puntos_disponibles') < $puntosSolicitados) {
                throw new \Exception('Puntos insuficientes');
            }

            //  Crear redención
            $redencion = Redencion::create([
                'distributor_id' => $distributorId,
                'direccion_id' => $direccionId,
                'fecha' => now(),
                'total_puntos_usados' => $puntosSolicitados,
            ]);

            //  Consumir FIFO
            foreach ($bolsas as $bolsa) {
                if ($restantes <= 0)
                    break;

                $usar = min($bolsa->puntos_disponibles, $restantes);

                // Detalle
                RedencionDetalle::create([
                    'redencion_id' => $redencion->id,
                    'bolsa_id' => $bolsa->id,
                    'puntos_usados' => $usar,
                ]);

                // Kardex
                KardexPuntos::create([
                    'distributor_id' => $distributorId,
                    'bolsa_id' => $bolsa->id,
                    'tipo' => 'consumo',
                    'puntos' => -$usar,
                    'descripcion' => 'Redención de puntos',
                    'fecha' => Carbon::now(),
                ]);

                // Actualizar bolsa
                $bolsa->decrement('puntos_disponibles', $usar);

                $restantes -= $usar;
            }
        });
    }
}