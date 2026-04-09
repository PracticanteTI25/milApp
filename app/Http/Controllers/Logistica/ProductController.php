<?php

namespace App\Http\Controllers\Logistica;

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
        // Trae productos con precio vigente para mostrarlo en la tabla
        $products = Product::with('currentPrice')->orderByDesc('id')->get();
        return view('areas.logistica.productos.index', compact('products'));
    }

    public function create()
    {
        return view('areas.logistica.productos.create');
    }

    public function store(Request $request)
    {
        // Validación: evita datos inválidos (OWASP)
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'presentation' => ['nullable', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
            'points' => ['required', 'integer', 'min:1'],

            // Imagen opcional
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Slug único basado en nombre
        $slug = Str::slug($data['name']);

        // Si el slug existe, le agregamos un sufijo
        $baseSlug = $slug;
        $i = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        // Guardar imagen (si viene)
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Guarda en storage/app/public/products
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'presentation' => $data['presentation'] ?? null,
            'stock' => $data['stock'],
            'active' => $request->boolean('active', true),
            'image_path' => $imagePath,
        ]);

        // Precio en puntos vigente (starts_at = ahora, ends_at = null)
        ProductPointPrice::create([
            'product_id' => $product->id,
            'points' => $data['points'],
            'starts_at' => now(),
            'ends_at' => null,
        ]);

        return redirect()->route('logistica.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        $product = Product::with('currentPrice')->findOrFail($id);
        return view('areas.logistica.productos.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('currentPrice')->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'presentation' => ['nullable', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
            'points' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Imagen nueva (si cargan)
        if ($request->hasFile('image')) {
            // Borra anterior si existe
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->presentation = $data['presentation'] ?? null;
        $product->stock = $data['stock'];
        $product->active = $request->boolean('active', true);
        $product->save();

        // Si cambia el precio en puntos, cerramos el precio vigente y creamos nuevo
        $current = $product->currentPrice;
        if (!$current || $current->points != $data['points']) {
            if ($current) {
                $current->ends_at = now();
                $current->save();
            }

            ProductPointPrice::create([
                'product_id' => $product->id,
                'points' => $data['points'],
                'starts_at' => now(),
                'ends_at' => null,
            ]);
        }

        return redirect()->route('logistica.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Borra imagen en storage
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('logistica.productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}