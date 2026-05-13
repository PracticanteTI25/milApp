<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    /**
     * Listado de productos con stock
     */
    public function index()
    {
        $products = Product::orderBy('name')->get();

        return view('areas.comercial.stock.index', compact('products'));
    }

    /**
     * Formulario de edición de stock
     */
    public function edit(Product $product)
    {
        return view('areas.comercial.stock.edit', compact('product'));
    }

    /**
     * Actualizar stock del producto
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'stock' => $request->stock,
        ]);

        return redirect()
            ->route('comercial.stock.index')
            ->with('success', 'Stock actualizado correctamente.');
    }
}
