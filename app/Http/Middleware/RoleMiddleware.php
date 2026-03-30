<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Middleware de autorización por rol.
     *
     * Uso en rutas:
     *   ->middleware(['auth.custom', 'role:Admin'])
     *   ->middleware(['auth.custom', 'role:Admin,Directivo'])
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = session('user');

        // Usuario no autenticado o sesión corrupta
        if (!$user || !is_array($user) || empty($user['rol'])) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No autenticado.',
                ], 401);
            }

            return redirect()->route('login');
        }

        // Usuario autenticado pero sin rol permitido
        if (!in_array($user['rol'], $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No autorizado.',
                ], 403);
            }

            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}