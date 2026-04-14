<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPointPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        return view('areas.logistica.productos.index', compact('products'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('areas.logistica.productos.create');
    }

    /**
     * Guardar producto + crear precio vigente.
     */
    public function store(Request $request)
    {
        /**
         *  Validación (OWASP: Input Validation)
         * - La imagen es opcional pero controlada por tipo y tamaño
         */
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'presentation' => ['nullable', 'string', 'max:255'],
            'stock'        => ['required', 'integer', 'min:0'],
            'active'       => ['nullable', 'boolean'],
            'points'       => ['required', 'integer', 'min:1'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Slug único (si se repite, le agregamos sufijo)
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        // Guardar imagen si viene (en storage/app/public/products)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Crear producto
        $product = Product::create([
            'name'         => $data['name'],
            'slug'         => $slug,          //texto limpio y amigable para identificar el recurso en la URL
            'description'  => $data['description'] ?? null,
            'presentation' => $data['presentation'] ?? null,
            'stock'        => $data['stock'],
            'active'       => $request->boolean('active', true),
            'image_path'   => $imagePath,
        ]);

        // Crear precio vigente
        ProductPointPrice::create([
            'product_id' => $product->id,
            'points'     => $data['points'],
            'starts_at'  => now(),
            'ends_at'    => null,
        ]);

        return redirect()
            ->route('logistica.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Formulario de edición.
     */
    public function edit(Product $product)
    {
        $product->load('currentPrice');
        return view('areas.logistica.productos.edit', compact('product'));
    }

    /**
     * Actualizar producto.
     * Si cambia el precio en puntos, cerramos el precio vigente y creamos uno nuevo (histórico).
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'presentation' => ['nullable', 'string', 'max:255'],
            'stock'        => ['required', 'integer', 'min:0'],
            'active'       => ['nullable', 'boolean'],
            'points'       => ['required', 'integer', 'min:1'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Actualizar imagen si viene una nueva
        if ($request->hasFile('image')) {
            // Borra imagen anterior si existe
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        // Actualizar campos base
        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->presentation = $data['presentation'] ?? null;
        $product->stock = $data['stock'];
        $product->active = $request->boolean('active', true);
        $product->save();

        // Manejo de histórico de puntos
        $product->load('currentPrice');
        $current = $product->currentPrice;

        if (!$current || (int)$current->points !== (int)$data['points']) {
            // Cerramos precio vigente (si existe)
            if ($current) {
                $current->ends_at = now();
                $current->save();
            }

            // Creamos nuevo precio vigente
            ProductPointPrice::create([
                'product_id' => $product->id,
                'points'     => $data['points'],
                'starts_at'  => now(),
                'ends_at'    => null,
            ]);
        }

        return redirect()
            ->route('logistica.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Eliminar producto (y su imagen si existe).
     */
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()
            ->route('logistica.productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
