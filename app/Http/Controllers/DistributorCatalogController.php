<?php

namespace App\Http\Controllers;

use App\Models\Product;

class DistributorCatalogController extends Controller
{
    public function index()
    {
        // Solo productos activos y con stock > 0
        $products = Product::with('currentPrice')  //precio actual
            ->where('active', true)   //solo productos activos
            ->where('stock', '>', 0)  //solo productos con stock disponible
            ->orderBy('name')  //ordenados alfabeticamente
            ->get();      //ejecuta la consulta y trae todos los productos que cumplen las condiciones

        return view('distribuidores.catalogo', compact('products'));
    }

    //detalle de un producto
    public function show($slug)
    {
        $product = Product::with('currentPrice')
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();   //si lo encuentra, lo devuelve, si no, error 404

        return view('distribuidores.producto', compact('product'));
    }
}