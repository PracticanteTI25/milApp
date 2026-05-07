<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPointPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\PermissionRegistry;

class ProductController extends Controller
{
    /**
     * Listado de productos con precio vigente.
     */
    public function index()
    {
        // Eager load del precio vigente para no hacer queries extra en la vista
        $products = Product::with('currentPrice')
            ->orderByDesc('id')
            ->get();

        return view('areas.comercial.productos.index', compact('products'));
    }

}
