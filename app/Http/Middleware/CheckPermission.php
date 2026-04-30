<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class CheckPermission
{
    public function handle($request, Closure $next, string $ability)
    {
        if (!Gate::allows($ability)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
