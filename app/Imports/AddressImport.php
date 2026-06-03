<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Distributor;
use App\Models\DistributorAddress;

class AddressImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        unset($rows[0]);

        foreach ($rows as $row) {

            if (count($row) < 9) continue;

            $document = trim($row[0]);

            $distributor = Distributor::where('document', $document)->first();

            if (!$distributor) continue;

            DistributorAddress::updateOrCreate(
                [
                    'distributor_id' => $distributor->id,
                    'address_line1'  => $row[2],
                ],
                [
                    'country'        => $row[1],
                    'address_line2'  => $row[3],
                    'city'           => $row[4],
                    'state'          => $row[5],
                    'postal_code'    => $row[6],
                    'phone'          => $row[7],
                    'is_default'     => (bool) $row[8],
                ]
            );
        }
    }
}
