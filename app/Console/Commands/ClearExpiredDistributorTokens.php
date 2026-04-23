<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DistributorLoginToken;

class ClearExpiredDistributorTokens extends Command
{
    protected $signature = 'tokens:clear-distributors';       //nombre del comando
    protected $description = 'Eliminar tokens de login de distribuidores usados o expirados';     //texto informaivo


    public function handle()
    {
        DistributorLoginToken::where(function ($q) {
            $q->where('expires_at', '<', now())     //si la fecha de expiracion ya paso, eliminar
                ->orWhereNotNull('used_at');        //si ya fue utilizado, eliminar
        })->delete();          //Borra todos los registros que cumplan esas condiciones

        return Command::SUCCESS;     //Laravel devuelve que todo salió bien
    }
}