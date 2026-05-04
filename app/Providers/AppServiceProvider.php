<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | GATES DE UX (UNO POR ÁREA)
        |--------------------------------------------------------------------------
        */

        $areas = [
            'directivo',
            'administrativo_financiero',
            'investigacion_desarrollo',
            'talento_humano',
            'nuevos_negocios_sac',
            'creativo',
            'marketing',
            'comercial',
            'operaciones',
            'abastecimiento',
            'calidad',
            'logistica_distribucion',
        ];

        foreach ($areas as $area) {
            Gate::define("view-area-{$area}", function ($user) use ($area) {

                // Admin ve todo
                if ($user->roles->contains('slug', 'admin')) {
                    return true;
                }

                // Área asignada
                if ($user->areas->contains('slug', $area)) {
                    return true;
                }

                // Rol con el mismo slug del área
                if ($user->roles->contains('slug', $area)) {
                    return true;
                }

                return false;
            });
        }

        /*
        |--------------------------------------------------------------------------
        | GATE DE SEGURIDAD (PERMISOS FUNCIONALES)
        |--------------------------------------------------------------------------
        */
        Gate::before(function ($user, string $ability) {

            // Superadmin
            if ($user->roles->contains('slug', 'admin')) {
                return true;
            }

            if (!str_contains($ability, '.')) {
                return null;
            }

            [$moduleSlug, $permissionSlug] = explode('.', $ability, 2);

            // Por rol
            if ($user->roles->isNotEmpty()) {
                $roleIds = $user->roles->pluck('id');

                $byRole = DB::table('role_permission')
                    ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
                    ->join('modules', 'modules.id', '=', 'permissions.module_id')
                    ->whereIn('role_permission.role_id', $roleIds)
                    ->where('modules.slug', $moduleSlug)
                    ->where('permissions.slug', $permissionSlug)
                    ->exists();

                if ($byRole) {
                    return true;
                }
            }

            // Por usuario
            return DB::table('user_permission')
                ->join('permissions', 'permissions.id', '=', 'user_permission.permission_id')
                ->join('modules', 'modules.id', '=', 'permissions.module_id')
                ->where('user_permission.user_id', $user->id)
                ->where('modules.slug', $moduleSlug)
                ->where('permissions.slug', $permissionSlug)
                ->exists();
        });

        Gate::define('view-admin-only', function ($user) {
            return $user->roles->contains('slug', 'admin');
        });
        
    }
}
