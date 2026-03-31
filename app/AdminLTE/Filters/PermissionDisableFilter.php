<?php

namespace App\AdminLte\Filters;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JeroenNoten\LaravelAdminlte\Menu\Filters\FilterInterface;

/**
 * Filtro AdminLTE:
 * - Muestra todos los ítems
 * - Habilita solo los permitidos
 * - Deshabilita visual y funcionalmente los demás
 */
class PermissionDisableFilter implements FilterInterface
{
    public function transform($item)
    {
        // Headers o strings no se tocan
        if (!is_array($item) || isset($item['header'])) {
            return $item;
        }

        // Si no define permiso, no aplicamos lógica
        if (!isset($item['permission'])) {
            return $item;
        }

        $user = Auth::user();

        // Sin usuario o sin rol → deshabilitar
        if (!$user || !$user->role_id) {
            return $this->disable($item);
        }

        // Esperamos formato modulo.accion
        if (!str_contains($item['permission'], '.')) {
            return $this->disable($item);
        }

        [$moduleSlug, $actionSlug] = explode('.', $item['permission'], 2);

        $hasPermission = DB::table('role_permission')
            ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
            ->join('modules', 'modules.id', '=', 'permissions.module_id')
            ->where('role_permission.role_id', $user->role_id)
            ->where('modules.slug', $moduleSlug)
            ->where('permissions.slug', $actionSlug)
            ->exists();

        return $hasPermission ? $item : $this->disable($item);
    }

    private function disable(array $item): array
    {
        // 🔑 Anular navegación REAL
        unset($item['route']);
        $item['url'] = '#';

        // 🔑 Clase visual correcta
        $item['class'] = trim(($item['class'] ?? '') . ' disabled opacity-50');

        // Tooltip
        $item['title'] = 'Acceso restringido';

        return $item;
    }
}
