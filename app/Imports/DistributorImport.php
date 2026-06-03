<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Distributor;

class DistributorImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Quitar encabezado
        $rows->shift();

        foreach ($rows as $row) {

            // convertir row a array
            $row = $row->toArray();

            if (count($row) < 4) {
                continue;
            }

            $document = trim($row[0]);

            if (!$document) {
                continue;
            }

            \Log::info('Importando distribuidor:', $row);

            Distributor::updateOrCreate(
                ['document' => $document],
                [
                    'email' => $row[1],
                    'name'  => $row[2],
                    'phone' => $row[3],
                ]
            );
        }
    }
}
