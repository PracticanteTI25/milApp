<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controlador del módulo Corporativo
 *
 * Este módulo funciona como un HUB de accesos corporativos
 * (formularios, portales, enlaces externos, etc.).
 *
 * IMPORTANTE:
 * - No es un área
 * - No usa permisos por ahora
 * - Visible para todo usuario autenticado
 */
class CorporativoController extends Controller
{
    /**
     * Vista principal del módulo Corporativo
     */
    public function index()
    {
        return view('corporativo.index');
    }
}
