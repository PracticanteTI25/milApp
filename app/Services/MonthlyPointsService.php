<?php

namespace App\Services;

use App\Models\DistributorMonthlyGoal;
use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\BusinessCalendarService;
use App\Models\BusinessSetting;
use App\Models\PointLot;

class MonthlyPointsService
{
    // Cierra el mes y decide congelación / habilitación en cascada
    public function cerrarMes(int $distributorId, Carbon $mes): void
    {
        DB::transaction(function () use ($distributorId, $mes) {

            $mes = $mes->copy()->startOfMonth();

            // META DEL MES (MODELO REAL)
            $metaActual = DistributorMonthlyGoal::where('distributor_id', $distributorId)
                ->where('year', $mes->year)
                ->where('month', $mes->month)
                ->first();

            if (!$metaActual) {
                return;
            }

            // BOLSAS PENDIENTES / CONGELADAS
            $bolsasPendientes = BolsaPuntos::where('distributor_id', $distributorId)
                ->whereIn('estado', ['pendiente', 'congelado'])
                ->where('mes', '<=', $mes)
                ->orderBy('mes')
                ->lockForUpdate()
                ->get();

            if ($bolsasPendientes->isEmpty()) {
                return;
            }

            // DEUDA ACUMULADA (METAS NO CUMPLIDAS)
            $deuda = DistributorMonthlyGoal::where('distributor_id', $distributorId)
                ->where(function ($q) use ($mes) {
                    $q->where('year', '<', $mes->year)
                        ->orWhere(function ($q2) use ($mes) {
                            $q2->where('year', $mes->year)
                                ->where('month', '<', $mes->month);
                        });
                })
                ->where('cumplida', false)
                ->sum('goal_amount');

            $montoNecesario = $metaActual->goal_amount + $deuda;

            // ¿SE CUMPLE META + DEUDA?
            if ($metaActual->achieved_amount < $montoNecesario) {

                // No se libera nada, se congela el mes actual
                BolsaPuntos::where('distributor_id', $distributorId)
                    ->where('mes', $mes)
                    ->update(['estado' => 'congelado']);

                return;
            }

            // SE CUMPLE TODO → HABILITAR BOLSAS
            $calendar = app(BusinessCalendarService::class);
            $fechaHabilitacion = $calendar
                ->firstBusinessDayOfMonth($mes->copy()->addMonth());

            $mesesVencimiento = (int) BusinessSetting::where('key', 'meses_vencimiento_puntos')
                ->value('value') ?? 3;

            foreach ($bolsasPendientes as $bolsa) {

                if ($bolsa->estado === 'habilitado') {
                    continue;
                }

                $bolsa->update([
                    'estado'             => 'habilitado',
                    'fecha_habilitacion' => $fechaHabilitacion,
                    'fecha_vencimiento'  => $fechaHabilitacion
                        ->copy()
                        ->addMonths($mesesVencimiento),
                ]);

                // KARDEX: FUENTE DE VERDAD
                KardexPuntos::create([
                    'distributor_id' => $distributorId,
                    'bolsa_id'       => $bolsa->id,
                    'tipo'           => 'habilitacion',
                    'puntos'         => 0,
                    'descripcion'    => 'Liberación por cumplimiento de metas acumuladas',
                    'fecha'          => now(),
                ]);
            }

            // MARCAR METAS COMO CUMPLIDAS
            DistributorMonthlyGoal::where('distributor_id', $distributorId)
                ->where(function ($q) use ($mes) {
                    $q->where('year', '<', $mes->year)
                        ->orWhere(function ($q2) use ($mes) {
                            $q2->where('year', $mes->year)
                                ->where('month', '<=', $mes->month);
                        });
                })
                ->update([
                    'cumplida'            => true,
                    'fecha_cumplimiento'  => now(),
                ]);
        });
    }

    /**
     * Vence puntos automáticamente según point_lots (ejecutar diario)
     *
     * Regla:
     * - El vencimiento REAL ocurre por lote
     * - La bolsa se ajusta como reflejo contable
     * - Kardex sigue siendo la fuente de auditoría
     */
    public function vencerBolsas(Carbon $fechaHoy): void
    {
        DB::transaction(function () use ($fechaHoy) {

            // ============================================
            // 1. OBTENER LOTES VENCIDOS (FUENTE REAL)
            // ============================================
            $expiredLots = PointLot::where('status', 'disponible')
                ->where('points_remaining', '>', 0)
                ->whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '<=', $fechaHoy)
                ->orderBy('fecha_vencimiento')
                ->lockForUpdate()
                ->get();

            foreach ($expiredLots as $lot) {

                $pointsToExpire = $lot->points_remaining;

                if ($pointsToExpire <= 0) {
                    continue;
                }

                // ============================================
                // 2. KARDEX: REGISTRAR VENCIMIENTO REAL
                // ============================================
                KardexPuntos::create([
                    'distributor_id' => $lot->distributor_id,
                    'bolsa_id'       => $lot->bolsa_id,
                    'tipo'           => 'vencimiento',
                    'impacto'        => 'resta',
                    'puntos'         => -$pointsToExpire,
                    'descripcion'    => 'Vencimiento automático de puntos',
                    'fecha'          => $fechaHoy,
                ]);

                // ============================================
                // 3. ACTUALIZAR LOTE (FUENTE REAL)
                // ============================================
                $lot->update([
                    'points_remaining' => 0,
                    'status'           => 'vencido',
                ]);

                // ============================================
                // 4. ACTUALIZAR BOLSA (REFLEJO CONTABLE)
                // ============================================
                $bag = BolsaPuntos::lockForUpdate()->find($lot->bolsa_id);

                if ($bag) {

                    // Descontar solo lo que realmente venció
                    $bag->decrement('puntos_generados', $pointsToExpire);

                    // Si estaban disponibles, también se descuentan
                    if ($bag->puntos_disponibles > 0) {
                        $use = min($bag->puntos_disponibles, $pointsToExpire);
                        $bag->decrement('puntos_disponibles', $use);
                    }

                    // Si ya no queda nada disponible, marcar estado
                    if ($bag->puntos_disponibles <= 0) {
                        $bag->update(['estado' => 'vencido']);
                    }
                }
            }
        });
    }
}
