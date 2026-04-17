<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use App\Services\RedemptionService;
use Illuminate\Http\Request;

class RedemptionController extends Controller
{
    /**
     * Procesa el canje del carrito.
     */
    public function store(Request $request, RedemptionService $redemptionService)
    {
        $distributor = auth('distributor')->user();

        try {
            // Tomamos el carrito desde sesión
            $cartItems = session('cart', []);

            // Ejecutamos la redención real
            $order = $redemptionService->redeem(
                distributorId: $distributor->id,
                cartItems: $cartItems
            );

            // Redirigir a confirmación
            return redirect()
                ->route('distribuidores.canje.confirmacion', $order)
                ->with('success', 'Canje realizado correctamente.');

        } catch (\Throwable $e) {

            // Error controlado (no mostramos detalles técnicos)
            return redirect()
                ->back()
                ->withErrors([
                    'canje' => $e->getMessage(),
                ]);
        }
    }
}
