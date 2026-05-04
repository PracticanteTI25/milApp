<?php

namespace App\Http\Controllers;

/**
 * ACCESO A ÁREAS SEGÚN PERMISOS
 * Controlador genérico para áreas.
 * Luego cada área tendrá su módulo completo.
 */
class AreaController extends Controller
{
    public function show(string $slug)
    {
        $user = auth()->user();

        // Admin entra siempre
        if ($user->roles->contains('slug', 'admin')) {
            return view('areas.show', compact('slug'));
        }

        // Usuario con área O rol asignado entra
        if (
            $user->areas->contains('slug', $slug) ||
            $user->roles->contains('slug', $slug)
        ) {
            return view('areas.show', compact('slug'));
        }

        // Si no cumple, se bloquea
        abort(403, 'No tienes acceso a este módulo');
    }
}
