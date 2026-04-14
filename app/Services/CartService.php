<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Obtiene el carrito actual desde sesión.
     */
    public function get(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Agrega un producto al carrito.
     *
     * Reglas críticas:
     * - El producto debe existir
     * - Debe estar activo
     * - Debe tener stock suficiente
     */
    public function add(int $productId, int $quantity = 1): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La cantidad debe ser mayor a cero.');
        }

        $product = Product::with('currentPrice')
            ->where('id', $productId)
            ->where('active', true)
            ->firstOrFail();

        if (!$product->currentPrice) {
            throw new \RuntimeException('El producto no tiene un precio vigente.');
        }

        if ($product->stock < $quantity) {
            throw new \RuntimeException('No hay stock suficiente para este producto.');
        }

        $cart = $this->get();

        // Si ya existe en el carrito, sumamos cantidad
        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['quantity'] + $quantity;

            if ($product->stock < $newQty) {
                throw new \RuntimeException('La cantidad total supera el stock disponible.');
            }

            $cart[$productId]['quantity'] = $newQty;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'points' => $product->currentPrice->points,
                'quantity' => $quantity,
                'image' => $product->image_path,
            ];
        }

        Session::put('cart', $cart);
    }

    /**
     * Vacía el carrito.
     */
    public function clear(): void
    {
        Session::forget('cart');
    }

    /**
     * Total de puntos del carrito.
     */
    public function totalPoints(): int
    {
        return collect($this->get())
            ->sum(fn($item) => $item['points'] * $item['quantity']);
    }
}
