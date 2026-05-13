<?php

namespace App\Http\Controllers\Financiera;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPointPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['currentPrice'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('areas.financiera.productos.index', compact('products'));
    }

    public function create()
    {
        return view('areas.financiera.productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'presentation' => 'nullable|string|max:255',
            'points' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // VALIDACIÓN
        ]);

        // Crear producto
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'presentation' => $request->presentation,
            'active' => $request->has('active'),
        ]);

        // GUARDAR IMAGEN
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');

            $product->update([
                'image_path' => $path,
            ]);
        }

        // Crear precio en puntos vigente
        ProductPointPrice::create([
            'product_id' => $product->id,
            'points' => $request->points,
            'starts_at' => now(),
        ]);

        return redirect()
            ->route('financiera.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $product->load('currentPrice');

        return view('areas.financiera.productos.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'presentation' => 'nullable|string|max:255',
            'points' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // VALIDACIÓN
        ]);

        // Actualizar producto
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'presentation' => $request->presentation,
            'active' => $request->has('active'),
        ]);

        // ACTUALIZAR IMAGEN
        if ($request->hasFile('image')) {

            // borrar imagen anterior
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $path = $request->file('image')->store('products', 'public');

            $product->update([
                'image_path' => $path,
            ]);
        }

        // Cerrar precio actual
        if ($product->currentPrice) {
            $product->currentPrice->update([
                'ends_at' => now(),
            ]);
        }

        // Crear nuevo precio
        ProductPointPrice::create([
            'product_id' => $product->id,
            'points' => $request->points,
            'starts_at' => now(),
        ]);

        return redirect()
            ->route('financiera.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }
}
