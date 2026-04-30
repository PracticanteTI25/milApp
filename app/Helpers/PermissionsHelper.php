<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

if (!function_exists('canUser')) {

    /**
     * Verifica si el usuario autenticado tiene un permiso funcional.
     *
     * @param string $permissionSlug
     * @return bool
     */
    function canUser(string $permissionSlug): bool
    {
        $user = Auth::user();  //obtiene el usuario que inicio sesion y lo autentica con Auth

        // Si no está autenticado
        if (!$user) {
            return false;
        }

        // Solo usuarios internos usan permisos funcionales
        if (!$user instanceof User) {
            return false;
        }

        return $user->allPermissions()->contains('slug', $permissionSlug);
    }
}
