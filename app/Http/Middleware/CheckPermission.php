<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class CheckPermission
{
    public function handle($request, Closure $next, string $ability)
    {
        // Soportar permisos múltiples separados por "|"
        $abilities = explode('|', $ability);

        foreach ($abilities as $singleAbility) {
            if (Gate::allows(trim($singleAbility))) {
                return $next($request);
            }
        }

        abort(403, 'No autorizado');
    }
}
