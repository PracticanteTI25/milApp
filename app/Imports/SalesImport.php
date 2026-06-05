<?php

namespace App\Imports;

use App\Models\Sale;
use App\Models\Distributor;
use App\Models\BolsaPuntos;
use App\Models\KardexPuntos;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Services\PointsService;
use Carbon\Carbon;

class SalesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // eliminar encabezado
        $rows->shift();

        foreach ($rows as $row) {

            if (count($row) < 4) {
                continue;
            }

            $document = trim($row[0]);
            $year     = (int) $row[1];
            $month    = (int) $row[2];
            $amount   = (float) $row[3];

            if (!$document || $month < 1 || $month > 12 || $year <= 0) {
                continue;
            }

            $distributor = Distributor::where('document', $document)->first();

            if (!$distributor) {
                continue;
            }

            // GUARDAR VENTA
            $sale = Sale::updateOrCreate(
                [
                    'distributor_id' => $distributor->id,
                    'year'           => $year,
                    'month'          => $month,
                ],
                [
                    'achieved_amount' => $amount
                ]
            );

            // MODO DE PROCESAMIENTO
            $modo = 'excel'; // cambia a 'api' en el futuro

            $pointsService = app(PointsService::class);
            $fecha = Carbon::create($year, $month, 1);

            if ($modo === 'excel') {

                // REEMPLAZA LO DEL MES (porque Excel es consolidado)

                $bolsa = BolsaPuntos::where('distributor_id', $distributor->id)
                    ->where('mes', $fecha)
                    ->first();

                if ($bolsa) {
                    KardexPuntos::where('bolsa_id', $bolsa->id)
                        ->where('tipo', 'generacion')
                        ->delete();

                    // conservar puntos manuales pero NO mezclarlos con los nuevos
                    $puntosDisponiblesActuales = $bolsa->puntos_disponibles;

                    // resetear solo generación automática
                    $bolsa->puntos_generados = 0;

                    // recalcular disponibles correctamente (solo lo que ya era manual)
                    $bolsa->puntos_disponibles = $puntosDisponiblesActuales;

                    $bolsa->save();
                }

                // generar puntos desde total mensual
                $pointsService->procesarVenta(
                    $distributor->id,
                    $fecha,
                    $amount
                );
            } else {

                // MODO API (futuro)
                // acumula puntos solo si es nueva venta

                $sale = Sale::where([
                    'distributor_id' => $distributor->id,
                    'year'           => $year,
                    'month'          => $month,
                ])->first();

                if ($sale && !$sale->wasRecentlyCreated) {
                    continue;
                }

                $pointsService->procesarVenta(
                    $distributor->id,
                    $fecha,
                    $amount
                );
            }
        }
    }
}
