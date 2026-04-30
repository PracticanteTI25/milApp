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
        /**
         * Gate global:
         * - Rol OR Usuario directo
         * - ability = modulo.slug
         */
        Gate::before(function ($user, string $ability) {


            if ($user->role && $user->role->slug === 'admin') {
                return true;
            }


            if (!str_contains($ability, '.')) {
                return null;
            }

            [$moduleSlug, $permissionSlug] = explode('.', $ability, 2);

            // 1. Permisos por ROL
            $byRole = DB::table('role_permission')
                ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
                ->join('modules', 'modules.id', '=', 'permissions.module_id')
                ->where('role_permission.role_id', $user->role_id)
                ->where('modules.slug', $moduleSlug)
                ->where('permissions.slug', $permissionSlug)
                ->exists();

            if ($byRole) {
                return true;
            }

            // 2. Permisos directos por USUARIO
            return DB::table('user_permission')
                ->join('permissions', 'permissions.id', '=', 'user_permission.permission_id')
                ->join('modules', 'modules.id', '=', 'permissions.module_id')
                ->where('user_permission.user_id', $user->id)
                ->where('modules.slug', $moduleSlug)
                ->where('permissions.slug', $permissionSlug)
                ->exists();
        });
    }
}
