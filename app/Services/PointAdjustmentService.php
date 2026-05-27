<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use App\Models\PointLot;
use App\Models\PointSetting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
     * - CREA un PointLot (nuevo)
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

            // UNA sola bolsa por mes (lógica existente)
            $bag = BolsaPuntos::firstOrCreate(
                [
                    'distributor_id' => $distributorId,
                    'mes'            => $month,
                ],
                [
                    'puntos_generados'   => 0,
                    'puntos_disponibles' => 0,
                ]
            );

            // Actualizar bolsa (lógica existente)
            $bag->increment('puntos_generados', $points);

            if ($initialState === 'habilitado') {
                $bag->increment('puntos_disponibles', $points);
            }

            // Kardex: FUENTE DE VERDAD (lógica existente)
            $kardex = KardexPuntos::create([
                'distributor_id' => $distributorId,
                'bolsa_id'       => $bag->id,
                'tipo'           => 'ajuste',

                // Impacto semántico
                'impacto'        => $initialState === 'habilitado'
                    ? 'suma_habilitada'
                    : 'suma_congelada',

                'puntos'         => $points,
                'descripcion'    => $reason,
                'fecha'          => now(),
            ]);

            // NUEVO: Crear LOTE DE PUNTOS (PointLot)

            // Configuración vigente
            $expirationMonths = PointSetting::first()->expiration_months;

            $isDisponible = $initialState === 'habilitado';

            // La fecha REAL de habilitación viene del kardex
            $fechaHabilitacion = $isDisponible ? $kardex->fecha : null;

            // La fecha de vencimiento solo existe si está habilitado
            $fechaVencimiento = $isDisponible
                ? Carbon::parse($fechaHabilitacion)->addMonths($expirationMonths)
                : null;

            PointLot::create([
                'distributor_id'     => $distributorId,
                'bolsa_id'           => $bag->id,
                'source'             => 'manual',
                'points_initial'     => $points,
                'points_remaining'   => $points,
                'fecha_habilitacion' => $fechaHabilitacion,
                'fecha_vencimiento'  => $fechaVencimiento,
                'status'             => $isDisponible ? 'disponible' : 'congelado',
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
                ->orderBy('mes') // FIFO por mes (lógica existente)
                ->lockForUpdate()
                ->get();

            $totalGenerados = $bags->sum('puntos_generados');

            if ($totalGenerados < $points) {
                throw new \RuntimeException('No hay puntos suficientes para realizar el ajuste.');
            }

            $remaining = $points;

            // DESCONTAR DE DISPONIBLES (BOLSAS)

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
                    'impacto'        => 'resta',
                    'puntos'         => -$use,
                    'descripcion'    => $reason . ' (descuento de disponibles)',
                    'fecha'          => now(),
                ]);

                $remaining -= $use;
            }

            // SI NO ALCANZÓ DISPONIBLES, TOCAR CONGELADOS
            if ($remaining > 0) {
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
                        'impacto'        => 'resta',
                        'puntos'         => -$use,
                        'descripcion'    => $reason . ' (descuento de congelados)',
                        'fecha'          => now(),
                    ]);

                    $remaining -= $use;
                }
            }

            // PASO 4: CONSUMO FIFO REAL DESDE POINT_LOTS

            $remainingToConsume = $points;

            $lots = PointLot::where('distributor_id', $distributorId)
                ->where('status', 'disponible')
                ->where('points_remaining', '>', 0)
                ->orderBy('fecha_vencimiento') // FIFO REAL POR VENCIMIENTO
                ->lockForUpdate()
                ->get();

            foreach ($lots as $lot) {

                if ($remainingToConsume <= 0) {
                    break;
                }

                $use = min($lot->points_remaining, $remainingToConsume);

                $lot->points_remaining -= $use;

                if ($lot->points_remaining === 0) {
                    $lot->status = 'consumido';
                }

                $lot->save();

                $remainingToConsume -= $use;
            }
        });
    }
}
