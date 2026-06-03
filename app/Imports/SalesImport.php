<?php

namespace App\Imports;

use App\Models\Sale;
use App\Models\Distributor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SalesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $rows->shift();

        foreach ($rows as $row) {

            //si la fila no tiene al menos 4 columnas la ignora
            if (count($row) < 4) {
                continue;
            }

            // extrae los datos
            $document = trim($row[0]);
            $year     = (int) $row[1];
            $month    = (int) $row[2];
            $amount   = (float) $row[3];

            // validaciones mínimas
            if (!$document || $month < 1 || $month > 12) continue;

            // busca en BD si existe el distribuidor
            $distributor = Distributor::where('document', $document)->first();

            // no crea nuevos, solo trabaja con los que ya existen
            if (!$distributor) {
                continue;
            }

            // busca un registro con esos campos, si existe, lo actualiza, si no, lo crea
            Sale::updateOrCreate(
                [
                    'distributor_id' => $distributor->id,
                    'year'           => $year,
                    'month'          => $month,
                ],
                [
                    'achieved_amount' => $amount     //valor de la venta
                ]
            );
        }
    }
}
