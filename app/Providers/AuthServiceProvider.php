<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {

            /*
            |--------------------------------------------------------------------------
            |  ADMIN: VE TODO
            |--------------------------------------------------------------------------
            */
            if ($user->roles()->where('slug', 'admin')->exists()) {
                return true;
            }

            /*
            |--------------------------------------------------------------------------
            |  PERMISOS FUNCIONALES (ACCIONES REALES)
            |--------------------------------------------------------------------------
            | Ejemplos:
            | - comercial.stock.editar
            | - financiera.productos.crear
            | - logistica.redenciones.exportar
            */
            if ($user->permissions()->where('slug', $ability)->exists()) {
                return true;
            }

            /*
            |--------------------------------------------------------------------------
            |  NAVEGACIÓN DE ÁREAS (SIDEBAR AdminLTE)
            |--------------------------------------------------------------------------
            | Regla:
            | - El usuario VE un área si:
            |   a) Tiene el área asignada explícitamente
            |   b) O tiene al menos un permiso funcional de esa área
            |
            | AdminLTE pregunta cosas como:
            | - view-area-comercial
            | - view-area-administrativo_financiero
            */
            if (str_starts_with($ability, 'view-area-')) {

                $areaSlug = str_replace('view-area-', '', $ability);

                /*
                | Mapeo explícito:
                | slug del área  => prefijos de permisos funcionales
                */
                $areaPermissionMap = [
                    'comercial' => [
                        'comercial.',
                    ],
                    'administrativo_financiero' => [
                        'financiera.',
                    ],
                    'logistica_distribucion' => [
                        'logistica.',
                    ],
                    // Otras áreas se agregan aquí cuando tengan permisos reales
                ];

                // Regla A: área asignada al usuario
                if ($user->areas()->where('slug', $areaSlug)->exists()) {
                    return true;
                }

                // Regla B: permisos funcionales del área
                if (isset($areaPermissionMap[$areaSlug])) {
                    foreach ($areaPermissionMap[$areaSlug] as $prefix) {
                        if (
                            $user->permissions()
                            ->where('slug', 'like', $prefix . '%')
                            ->exists()
                        ) {
                            return true;
                        }
                    }
                }

                // No tiene área ni permisos de esa área
                return false;
            }

            return false;
        });
    }
}
