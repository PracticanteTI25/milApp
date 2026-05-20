<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use App\Models\Redencion;
use App\Models\RedencionDetalle;
use Illuminate\Support\Facades\DB;
use Exception;

class RedemptionService
{
    /**
     * Ejecuta una redención de puntos usando FIFO sobre bolsas habilitadas.
     *
     * @throws Exception
     */
    public function redimir(
        int $distributorId,
        int $direccionId,
        int $puntosSolicitados,
        string $descripcion = 'Redención de puntos'
    ): Redencion {
        if ($puntosSolicitados <= 0) {
            throw new Exception('Los puntos a canjear deben ser mayores a cero.');
        }

        return DB::transaction(function () use (
            $distributorId,
            $direccionId,
            $puntosSolicitados,
            $descripcion
        ) {

            //  Bolsas habilitadas FIFO (por vencimiento)
            $bolsas = BolsaPuntos::where('distributor_id', $distributorId)
                ->where('puntos_disponibles', '>', 0)
                ->orderBy('fecha_vencimiento')
                ->lockForUpdate()
                ->get();

            $totalDisponible = $bolsas->sum('puntos_disponibles');

            if ($totalDisponible < $puntosSolicitados) {
                throw new Exception('Puntos insuficientes para completar la redención.');
            }

            // Crear redención
            $redencion = Redencion::create([
                'distributor_id' => $distributorId,
                'direccion_id' => $direccionId,
                'fecha' => now(),
                'total_puntos_usados' => $puntosSolicitados,
            ]);

            $restantes = $puntosSolicitados;

            // Consumo FIFO (canje parcial interno)
            foreach ($bolsas as $bolsa) {

                if ($restantes <= 0) {
                    break;
                }

                $usar = min($bolsa->puntos_disponibles, $restantes);

                // Detalle de redención
                RedencionDetalle::create([
                    'redencion_id' => $redencion->id,
                    'bolsa_id' => $bolsa->id,
                    'puntos_usados' => $usar,
                ]);

                // Kardex (tipo unificado)
                KardexPuntos::create([
                    'distributor_id' => $distributorId,
                    'bolsa_id' => $bolsa->id,
                    'tipo' => 'canje',
                    'puntos' => -$usar,
                    'descripcion' => $descripcion,
                    'fecha' => now(),
                ]);

                // Actualizar bolsa
                $bolsa->decrement('puntos_disponibles', $usar);

                $restantes -= $usar;
            }

            return $redencion;
        });
    }
}
