<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Services\PointsReadService;

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

    public function index(CartService $cartService, PointsReadService $pointsReadService)
    {
        $cartItems = $cartService->get();
        $totalPoints = $cartService->totalPoints();

        $distributor = auth('distributor')->user();

        // Fuente de verdad: sistema nuevo de puntos
        $resumenPuntos = $pointsReadService->resumen($distributor->id);

        $availablePoints = $resumenPuntos['disponibles'];

        return view('distribuidores.carrito', [
            'cartItems'       => $cartItems,
            'totalPoints'     => $totalPoints,
            'availablePoints' => $availablePoints,
        ]);
    }

    public function update(Request $request, CartService $cartService)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $cartService->updateQuantity(
                productId: $data['product_id'],
                quantity: $data['quantity']
            );

            return redirect()->route('distribuidores.carrito.index');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withErrors(['cart' => $e->getMessage()]);
        }
    }

    public function remove(Request $request, CartService $cartService)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cartService->remove($data['product_id']);

        return redirect()->route('distribuidores.carrito.index');
    }
}
