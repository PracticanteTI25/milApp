<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {

        /**
         * Gate global para permisos tipo "modulo.accion"
         *
         * Esto permite que AdminLTE ('can' => 'usuarios.ver') funcione
         * usando TU sistema de permisos en base de datos.
         *
         * Seguridad:
         * - No confía en frontend
         * - Consulta role_permission real
         */
        Gate::before(function ($user, string $ability) {

            // Si la habilidad no tiene formato modulo.accion, no la manejamos aquí.
            if (!str_contains($ability, '.')) {
                return null; // deja que otros gates/policies respondan
            }

            // Si el usuario no tiene rol asignado, no puede hacer nada
            if (!$user || !$user->role_id) {
                return false;
            }

            [$moduleSlug, $actionSlug] = explode('.', $ability, 2);

            $hasPermission = DB::table('role_permission')
                ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
                ->join('modules', 'modules.id', '=', 'permissions.module_id')
                ->where('role_permission.role_id', $user->role_id)
                ->where('modules.slug', $moduleSlug)
                ->where('permissions.slug', $actionSlug)
                ->exists();

            return $hasPermission; // true = permitido, false = denegado
        });
    }

    // URL::forceRootUrl('https://milapp.grupomilagros.co/milApp_nuevo/public');
    // URL::forceScheme('https');

}