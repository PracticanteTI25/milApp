<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Agregar producto al carrito.
     */
    public function add(Request $request, CartService $cartService)
    {
        //Validación básica de entrada (OWASP: Input Validation)
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $cartService->add(
                productId: $data['product_id'],
                quantity: $data['quantity']
            );

            return redirect()
                ->back()
                ->with('success', 'Producto agregado al carrito.');

        } catch (\Throwable $e) {
            // Mensaje controlado, sin exponer errores internos
            return redirect()
                ->back()
                ->withErrors([
                    'cart' => $e->getMessage(),
                ]);
        }
    }



public function index(CartService $cartService)
{
    $cartItems = $cartService->get();
    $totalPoints = $cartService->totalPoints();

    // Distribuidora autenticada
    $distributor = auth('distributor')->user();

    // Puntos disponibles (campo que ya manejas)
    $availablePoints = $distributor->points_balance ?? 0;

    return view('distribuidores.carrito', [
        'cartItems'       => $cartItems,
        'totalPoints'     => $totalPoints,
        'availablePoints' => $availablePoints,
    ]);
}

}