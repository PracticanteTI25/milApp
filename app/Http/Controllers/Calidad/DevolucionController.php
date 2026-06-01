<?php

namespace App\Http\Controllers\Calidad;

use App\Http\Controllers\Controller;
use App\Models\Devolucion;

class DevolucionController extends Controller
{
    public function index()
    {
        // dd(auth()->user());

        // Trae devoluciones con distribuidor
        $devoluciones = Devolucion::with('distributor')
            ->latest()
            ->get();

        return view('calidad.devoluciones.index', compact('devoluciones'));
    }
}
