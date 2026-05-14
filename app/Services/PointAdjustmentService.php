<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use Illuminate\Support\Facades\DB;

class PointAdjustmentService
{
    /**
     * Ajuste manual POSITIVO de puntos
     *
     * Reglas:
     * - Siempre suma a puntos_generados
     * - Solo suma a puntos_disponibles si el ajuste es "habilitado"
     * - NO modifica puntos existentes
     * - Usa una sola bolsa mensual
     */
    public function addPoints(
        int $distributorId,
        int $points,
        string $initialState, // habilitado | congelado
        string $reason
    ): void {

        if (!in_array($initialState, ['habilitado', 'congelado'])) {
            throw new \InvalidArgumentException('Estado inicial inválido.');
        }

        if ($points <= 0) {
            throw new \InvalidArgumentException('Los puntos deben ser mayores que cero.');
        }

        DB::transaction(function () use ($distributorId, $points, $initialState, $reason) {

            $month = now()->startOfMonth();

            // UNA sola bolsa por mes
            $bag = BolsaPuntos::firstOrCreate(
                [
                    'distributor_id' => $distributorId,
                    'mes' => $month,
                ],
                [
                    'puntos_generados' => 0,
                    'puntos_disponibles' => 0,
                ]
            );

            // Siempre suma a generados (existencia contable)
            $bag->increment('puntos_generados', $points);

            // Solo suma a disponibles si el ajuste entra habilitado
            if ($initialState === 'habilitado') {
                $bag->increment('puntos_disponibles', $points);
            }

            // Kardex: fuente de verdad
            KardexPuntos::create([
                'distributor_id' => $distributorId,
                'bolsa_id'       => $bag->id,
                'tipo'           => 'ajuste',

                //  IMPACTO SEMÁNTICO
                'impacto'        => $initialState === 'habilitado'
                    ? 'suma_habilitada'
                    : 'suma_congelada',

                'puntos'         => $points,
                'descripcion'    => $reason,
                'fecha'          => now(),
            ]);
        });
    }

    /**
     * Ajuste manual NEGATIVO de puntos (eliminación / corrección)
     *
     * Reglas:
     * - Resta de puntos_generados
     * - Resta de puntos_disponibles SOLO si hay disponibles
     * - Respeta FIFO
     * - Nunca deja saldos negativos
     */
    public function subtractPoints(
        int $distributorId,
        int $points,
        string $reason
    ): void {

        if ($points <= 0) {
            throw new \InvalidArgumentException('Los puntos a restar deben ser mayores que cero.');
        }

        DB::transaction(function () use ($distributorId, $points, $reason) {

            $bags = BolsaPuntos::where('distributor_id', $distributorId)
                ->orderBy('mes') // FIFO por mes
                ->lockForUpdate()
                ->get();

            $totalDisponibles = $bags->sum('puntos_disponibles');
            $totalGenerados   = $bags->sum('puntos_generados');

            if ($totalGenerados < $points) {
                throw new \RuntimeException('No hay puntos suficientes para realizar el ajuste.');
            }

            $remaining = $points;

            // DESCONTAR DE DISPONIBLES (OBLIGATORIO)

            foreach ($bags as $bag) {
                if ($remaining <= 0) {
                    break;
                }

                if ($bag->puntos_disponibles <= 0) {
                    continue;
                }

                $use = min($bag->puntos_disponibles, $remaining);

                $bag->decrement('puntos_disponibles', $use);
                $bag->decrement('puntos_generados', $use);

                KardexPuntos::create([
                    'distributor_id' => $distributorId,
                    'bolsa_id'       => $bag->id,
                    'tipo'           => 'ajuste',

                    //IMPACTO
                    'impacto'        => 'resta',

                    'puntos'         => -$use,
                    'descripcion'    => $reason . ' (descuento de disponibles)',
                    'fecha'          => now(),
                ]);

                $remaining -= $use;
            }

            //SOLO SI NO ALCANZÓ DISPONIBLES, TOCAR CONGELADOS

            if ($remaining > 0) {

                // Guard clause: aquí ya sabemos que disponibles NO alcanzaron
                foreach ($bags as $bag) {
                    if ($remaining <= 0) {
                        break;
                    }

                    $congelados = $bag->puntos_generados - $bag->puntos_disponibles;

                    if ($congelados <= 0) {
                        continue;
                    }

                    $use = min($congelados, $remaining);

                    $bag->decrement('puntos_generados', $use);

                    KardexPuntos::create([
                        'distributor_id' => $distributorId,
                        'bolsa_id'       => $bag->id,
                        'tipo'           => 'ajuste',

                        // IMPACTO
                        'impacto'        => 'resta',

                        'puntos'         => -$use,
                        'descripcion'    => $reason . ' (descuento de congelados)',
                        'fecha'          => now(),
                    ]);

                    $remaining -= $use;
                }
            }
        });
    }
}
