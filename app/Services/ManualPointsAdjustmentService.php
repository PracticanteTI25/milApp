<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManualPointsAdjustmentService
{
    public function ajustar(
        int $distributorId,
        int $puntos,              // puede ser positivo o negativo
        string $estadoInicial,    // congelado | habilitado
        string $comentario
    ): void {
        if (!in_array($estadoInicial, ['congelado', 'habilitado'])) {
            throw new \InvalidArgumentException('Estado no permitido para ajuste manual');
        }

        if (trim($comentario) === '') {
            throw new \InvalidArgumentException('El comentario es obligatorio');
        }

        DB::transaction(function () use ($distributorId, $puntos, $estadoInicial, $comentario) {

            // Usamos el mes actual como referencia
            $mes = Carbon::now()->startOfMonth();  //startOfMonth() modifica la fecha para que sea día 1 hora cero

            // Obtener o crear bolsa del mes
            $bolsa = BolsaPuntos::firstOrCreate(
                [
                    'distributor_id' => $distributorId,
                    'mes' => $mes,
                ],
                [
                    'puntos_generados' => 0,
                    'puntos_disponibles' => 0,
                    'estado' => $estadoInicial,
                ]
            );

            // Aplicar ajuste
            $bolsa->increment('puntos_generados', $puntos);
            $bolsa->increment('puntos_disponibles', $puntos);

            // Kardex
            KardexPuntos::create([
                'distributor_id' => $distributorId,
                'bolsa_id' => $bolsa->id,
                'tipo' => 'ajuste',
                'puntos' => $puntos,
                'descripcion' => $comentario,
                'fecha' => Carbon::now(),
            ]);
        });
    }



    public function ajustarNegativo(
        int $distributorId,
        int $puntosARestar,   // número POSITIVO (ej: 5)
        string $comentario
    ): void {
        if ($puntosARestar <= 0) {
            throw new \InvalidArgumentException('Los puntos a restar deben ser mayores que cero');
        }

        if (trim($comentario) === '') {
            throw new \InvalidArgumentException('El comentario es obligatorio');
        }

        DB::transaction(function () use ($distributorId, $puntosARestar, $comentario) {

            //  Bolsas elegibles FIFO
            $bolsas = BolsaPuntos::where('distributor_id', $distributorId)
                ->whereIn('estado', ['habilitado', 'congelado'])
                ->where('puntos_disponibles', '>', 0)
                ->orderByRaw("FIELD(estado, 'habilitado', 'congelado')")
                ->orderBy('fecha_habilitacion')
                ->lockForUpdate()
                ->get();

            $totalDisponible = $bolsas->sum('puntos_disponibles');
            if ($totalDisponible < $puntosARestar) {
                throw new \RuntimeException('No hay puntos suficientes para realizar el ajuste');
            }

            $restantes = $puntosARestar;

            //  Descontar FIFO
            foreach ($bolsas as $bolsa) {
                if ($restantes <= 0)
                    break;

                $usar = min($bolsa->puntos_disponibles, $restantes);

                // Kardex por bolsa
                KardexPuntos::create([
                    'distributor_id' => $distributorId,
                    'bolsa_id' => $bolsa->id,
                    'tipo' => 'ajuste',
                    'puntos' => -$usar,
                    'descripcion' => $comentario,
                    'fecha' => Carbon::now(),
                ]);

                // Actualizar bolsa
                $bolsa->decrement('puntos_disponibles', $usar);

                $restantes -= $usar;
            }
        });
    }
}