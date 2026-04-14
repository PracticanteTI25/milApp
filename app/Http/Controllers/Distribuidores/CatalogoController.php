<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use App\Models\Product;

class CatalogoController extends Controller
{
    /**
     * Catálogo visible para distribuidoras (solo lectura).
     */
    public function index()
    {
        $products = Product::with('currentPrice')
            ->where('active', true)
            ->where('stock', '>', 0)
            ->whereHas('currentPrice') // solo productos con precio vigente
            ->orderBy('name')
            ->get();

        return view('distribuidores.catalogo', compact('products'));
    }
}