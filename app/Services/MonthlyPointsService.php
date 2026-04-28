<?php

namespace App\Services;

use App\Models\Meta;
use App\Models\BolsaPuntos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\BusinessCalendarService;
use App\Models\KardexPuntos;


class MonthlyPointsService
{
    public function cerrarMes(int $distributorId, Carbon $mes): void
    {
        DB::transaction(function () use ($distributorId, $mes) {

            $meta = Meta::where('distributor_id', $distributorId)
                ->where('mes', $mes->copy()->startOfMonth())
                ->first();

            $bolsa = BolsaPuntos::where('distributor_id', $distributorId)
                ->where('mes', $mes->copy()->startOfMonth())
                ->first();

            if (!$meta || !$bolsa) {
                return;
            }

            // Si no cumplió la meta → congelado
            if (!$meta->cumplida) {
                $bolsa->update(['estado' => 'congelado']);
                return;
            }

            // Si cumplió → se habilita el próximo mes
            $calendar = app(BusinessCalendarService::class);
            $fechaHabilitacion = $calendar
                ->firstBusinessDayOfMonth($mes->copy()->addMonth());

            $bolsa->update([
                'estado' => 'habilitado',
                'fecha_habilitacion' => $fechaHabilitacion,
                'fecha_vencimiento' => $fechaHabilitacion->copy()->addMonths(3),
            ]);
        });
    }

    public function vencerBolsas(Carbon $fechaHoy): void
    {
        DB::transaction(function () use ($fechaHoy) {

            $bolsas = BolsaPuntos::where('estado', 'habilitado')
                ->whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '<=', $fechaHoy)
                ->orderBy('fecha_vencimiento') // FIFO temporal
                ->lockForUpdate()
                ->get();

            foreach ($bolsas as $bolsa) {

                // Registrar kardex antes de vencer
                KardexPuntos::create([
                    'distributor_id' => $bolsa->distributor_id,
                    'bolsa_id' => $bolsa->id,
                    'tipo' => 'vencimiento',
                    'puntos' => $bolsa->puntos_disponibles,
                    'descripcion' => 'Vencimiento automático de puntos',
                    'fecha' => $fechaHoy,
                ]);

                // Vencer bolsa
                $bolsa->update([
                    'estado' => 'vencido',
                    'puntos_disponibles' => 0,
                ]);
            }
        });
    }
}
