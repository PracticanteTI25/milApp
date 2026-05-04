<?php

namespace App\Services;

use App\Models\User;

class PermissionService
{
    /**
     * Devuelve los módulos habilitados en el sidebar
     * usando SOLO áreas y roles (UX).
     */
    public function getViewableModulesForUser(User $user): array
    {
        $modules = collect();

        // Áreas habilitan módulos
        if ($user->areas && $user->areas->isNotEmpty()) {
            $modules = $modules->merge(
                $user->areas->pluck('slug')
            );
        }

        // Roles habilitan módulos
        if ($user->roles && $user->roles->isNotEmpty()) {
            $modules = $modules->merge(
                $user->roles->pluck('slug')
            );
        }

        return $modules
            ->unique()
            ->values()
            ->toArray();
    }
}
