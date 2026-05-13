<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // NO gates aquí
        // NO permisos aquí
        // NO áreas aquí

        // Este provider queda solo para:
        // - macros
        // - configuraciones globales
        // - ajustes de framework
    }
}
