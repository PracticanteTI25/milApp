<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Distributor;
use App\Models\AuthorizedDistributor;

class SyncAuthorizedDistributors extends Command
{
    protected $signature = 'distributors:sync-authorized';
    protected $description = 'Sincroniza distribuidores con tabla de autorizados';

    public function handle()
    {
        $count = 0;

        $distributors = Distributor::whereNotNull('email')->get();

        foreach ($distributors as $d) {

            AuthorizedDistributor::updateOrCreate(
                ['email' => $d->email],
                [
                    'document' => $d->document,
                    'active'   => true
                ]
            );

            $count++;
        }

        $this->info(" {$count} distribuidores sincronizados correctamente.");
    }
}
