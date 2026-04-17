<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;   //usa la sesion de laravel, o sea esta en memoria del usuario (temporal)

class CartService
{
    /**
     * Obtiene el carrito actual desde sesión.
     */
    public function get(): array
    {
        return Session::get('cart', []);    //cart es el nombre de la sesion y [] es el valor por defecto si no existe
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
            throw new \InvalidArgumentException('La cantidad debe ser mayor a cero');
        }

        //busca el producto y verifica las reglas 
        $product = Product::with('currentPrice')
            ->where('id', $productId)
            ->where('active', true)
            ->firstOrFail();

        //sin precio no se puede canjear
        if (!$product->currentPrice) {
            throw new \RuntimeException('El producto no tiene un precio vigente');
        }

        //se evita vender más de lo que hay disponible
        if ($product->stock < $quantity) {
            throw new \RuntimeException('No hay stock suficiente para este producto');
        }

        //obtiene el carrito actual
        $cart = $this->get();

        // Si ya existe en el carrito, sumamos cantidad
        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['quantity'] + $quantity;

            if ($product->stock < $newQty) {
                throw new \RuntimeException('La cantidad total supera el stock disponible');
            }

            $cart[$productId]['quantity'] = $newQty;   //actualiza la cantidad
        } else {         //si no existe, crea nuevo item en el carrito
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'points' => $product->currentPrice->points,
                'quantity' => $quantity,
                'image' => $product->image_path,
            ];
        }

        Session::put('cart', $cart);  //persistencia del carrito
    }

    /**
     * Vacía/elimina el carrito.
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

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Cantidad inválida.');
        }

        $cart = $this->get();

        //verifica exisencia, para evitar actualizar algo que no existe
        if (!isset($cart[$productId])) {
            throw new \RuntimeException('Producto no existe en el carrito');
        }

        //valida el producto segun las reglas
        $product = Product::with('currentPrice')
            ->where('id', $productId)
            ->where('active', true)
            ->firstOrFail();

        if ($product->stock < $quantity) {
            throw new \RuntimeException('Stock insuficiente');
        }

        $cart[$productId]['quantity'] = $quantity;  //actualiza

        Session::put('cart', $cart);      //guarda la actualizacion
    }

    /**
     * Elimina un producto del carrito
     */
    public function remove(int $productId): void
    {
        $cart = $this->get();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
    }


}
