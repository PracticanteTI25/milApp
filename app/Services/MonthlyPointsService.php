<?php

namespace App\Services;

use App\Models\Meta;
use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\BusinessCalendarService;
use App\Models\BusinessSetting;

class MonthlyPointsService
{
    /**
     * Cierra el mes y decide congelación / habilitación en cascada
     */
    public function cerrarMes(int $distributorId, Carbon $mes): void
    {
        DB::transaction(function () use ($distributorId, $mes) {

            $mes = $mes->copy()->startOfMonth();

            // Meta del mes actual
            $metaActual = Meta::where('distributor_id', $distributorId)
                ->where('mes', $mes)
                ->first();

            if (!$metaActual) {
                return;
            }

            // Bolsas pendientes/congeladas hasta este mes (orden cronológico)
            $bolsasPendientes = BolsaPuntos::where('distributor_id', $distributorId)
                ->whereIn('estado', ['pendiente', 'congelado'])
                ->where('mes', '<=', $mes)
                ->orderBy('mes')
                ->lockForUpdate()
                ->get();

            if ($bolsasPendientes->isEmpty()) {
                return;
            }

            // Deuda acumulada (metas no cumplidas)
            $deuda = Meta::where('distributor_id', $distributorId)
                ->where('mes', '<', $mes)
                ->where('cumplida', false)
                ->sum('meta_monto');

            $montoNecesario = $metaActual->meta_monto + $deuda;

            // ¿Se compensa todo?
            if ($metaActual->monto_logrado < $montoNecesario) {
                // No se libera nada, solo se congela el mes actual
                BolsaPuntos::where('distributor_id', $distributorId)
                    ->where('mes', $mes)
                    ->update(['estado' => 'congelado']);

                return;
            }

            // Sí se compensa → liberar TODAS las bolsas pendientes
            $calendar = app(BusinessCalendarService::class);
            $fechaHabilitacion = $calendar->firstBusinessDayOfMonth($mes->copy()->addMonth());

            // DEFAULT TEMPORAL: 3 meses
            // Este valor se sobreescribe cuando exista la configuración en el panel administrativo
            $mesesVencimiento = (int) BusinessSetting::where('key', 'meses_vencimiento_puntos')
                ->value('value') ?? 3;

            // IMPORTANTE:
            // El vencimiento empieza a contarse desde la FECHA DE HABILITACIÓN.
            // Si la regla cambia, modificar SOLO esta suma de meses.

            foreach ($bolsasPendientes as $bolsa) {

                if ($bolsa->estado === 'habilitado') {
                    continue;
                }

                $bolsa->update([
                    'estado' => 'habilitado',
                    'fecha_habilitacion' => $fechaHabilitacion,
                    //el vencimiento se calcula desde la fecha de habilitacion, si la regla de negocio cambia, modficar SOLO esta linea
                    'fecha_vencimiento' => $fechaHabilitacion->copy()->addMonths($mesesVencimiento),
                ]);

                KardexPuntos::create([
                    'distributor_id' => $distributorId,
                    'bolsa_id' => $bolsa->id,
                    'tipo' => 'habilitacion',
                    'puntos' => 0, // IMPORTANTE: no altera el saldo
                    'descripcion' => 'Liberación por cumplimiento de metas acumuladas',
                    'fecha' => now(),
                ]);
            }

            // Marcar metas como cumplidas
            Meta::where('distributor_id', $distributorId)
                ->where('mes', '<=', $mes)
                ->update([
                    'cumplida' => true,
                    'fecha_cumplimiento' => now(),
                ]);
        });
    }

    /**
     * Vence bolsas automáticamente (ejecutar diario)
     */
    public function vencerBolsas(Carbon $fechaHoy): void
    {
        DB::transaction(function () use ($fechaHoy) {

            $bolsas = BolsaPuntos::where('estado', 'habilitado')
                ->whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '<=', $fechaHoy)
                ->orderBy('fecha_vencimiento')
                ->lockForUpdate()
                ->get();

            foreach ($bolsas as $bolsa) {

                if ($bolsa->puntos_disponibles <= 0) {
                    $bolsa->update(['estado' => 'vencido']);
                    continue;
                }

                KardexPuntos::create([
                    'distributor_id' => $bolsa->distributor_id,
                    'bolsa_id' => $bolsa->id,
                    'tipo' => 'vencimiento',
                    'puntos' => -$bolsa->puntos_disponibles,
                    'descripcion' => 'Vencimiento automático de puntos',
                    'fecha' => $fechaHoy,
                ]);

                $bolsa->update([
                    'estado' => 'vencido',
                    'puntos_disponibles' => 0,
                ]);
            }
        });
    }
}
