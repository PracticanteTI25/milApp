<?php

namespace App\Services;

use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PointAdjustmentService
{
    //suma puntos en la bolsa del mes y deja registro
    public function addPoints(
        int $distributorId,
        int $points,
        string $initialState,
        string $reason
    ): void {
        //solo permitir habilitado y congelado
        if (!in_array($initialState, ['habilitado', 'congelado'])) {
            throw new \InvalidArgumentException('Estado inicial inválido.');
        }

        //evalua si TODO se guarda o NADA se guarda
        DB::transaction(function () use ($distributorId, $points, $initialState, $reason) {

            $month = Carbon::now()->startOfMonth();  //obtiene el mes actual

            //si existe bolsa del mes la usa, si no existe, la crea
            $bag = BolsaPuntos::firstOrCreate(
                [
                    'distributor_id' => $distributorId,
                    'mes' => $month,
                ],
                [
                    'puntos_generados' => 0,
                    'puntos_disponibles' => 0,
                    'estado' => $initialState,
                ]
            );

            //incrementar puntos
            $bag->increment('puntos_generados', $points);
            $bag->increment('puntos_disponibles', $points);

            //registra en el kardex para trazabilidad
            KardexPuntos::create([
                'distributor_id' => $distributorId,
                'bolsa_id' => $bag->id,
                'tipo' => 'ajuste',
                'puntos' => $points,
                'descripcion' => $reason,
                'fecha' => now(),
            ]);
        });
    }

    // Ajuste negativo de puntos (FIFO) y registrro
    public function subtractPoints(
        int $distributorId,
        int $points,
        string $reason
    ): void {
        DB::transaction(function () use ($distributorId, $points, $reason) {

            $bags = BolsaPuntos::where('distributor_id', $distributorId)
                ->where('puntos_disponibles', '>', 0)
                ->orderByRaw("FIELD(estado, 'habilitado', 'congelado')")  //primero usa bolsas habilitadas y luego las congeladas
                ->orderBy('fecha_habilitacion')  //las mas antiguas primero (FIFO)
                ->lockForUpdate()  //evita que otro proceso use los mismo puntos al mismo tiempo
                ->get();

            $available = $bags->sum('puntos_disponibles'); //saldo total

            if ($available < $points) {
                throw new \RuntimeException('La distribuidora no tiene puntos suficientes.');
            }

            $remaining = $points;

            //recorre bolsa por bolsa
            foreach ($bags as $bag) {
                if ($remaining <= 0) {
                    break;
                }

                $use = min($bag->puntos_disponibles, $remaining);   //toma solo lo nencesario

                $bag->decrement('puntos_disponibles', $use);      //descuenta

                //registar en el kardex
                KardexPuntos::create([
                    'distributor_id' => $distributorId,
                    'bolsa_id' => $bag->id,
                    'tipo' => 'ajuste',
                    'puntos' => -$use,
                    'descripcion' => $reason,
                    'fecha' => now(),
                ]);

                $remaining -= $use;
            }
        });
    }
}
