<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Distribuidores\CheckoutConfirmRequest;
use App\Services\RedemptionService;
use Illuminate\Support\Facades\DB;
use App\Models\RedencionProducto;

class CheckoutController extends Controller
{
    /**
     * Muestra la pantalla de checkout de canje.
     */
    public function show(Request $request)
    {
        $distributor = auth('distributor')->user();

        // El carrito ya existe en sesión
        $cart = session('cart', []);

        // Validación básica: no entrar si el carrito está vacío
        if (empty($cart)) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'No tienes productos en el carrito.');
        }

        /*
         * IMPORTANTE:
         * - Aquí aún NO llamamos a APIs
         * - Aquí aún NO ejecutamos el canje
         * - Solo mostramos datos
         */

        return view('distribuidores.checkout.index', [
            'distributor' => $distributor,
            'cart' => $cart,

            // TEMPORAL: direcciones vendrán de BD / API luego
            'direcciones' => [],

            // Estos datos luego vendrán de la API
            'nit' => null,
            'telefono' => null,
        ]);
    }

    /**
     * Confirmación del canje (se implementa en el siguiente paso).
     */

    public function confirm(
        CheckoutConfirmRequest $request,
        RedemptionService $redemptionService
    ) {
        $distributor = auth('distributor')->user();

        // 1) Obtener carrito
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'El carrito está vacío.');
        }

        // 2) Calcular puntos totales del carrito
        //    (puntos por caja * cantidad de cajas)
        $totalPuntos = collect($cart)->sum(function ($item) {
            return ($item['points'] ?? 0) * ($item['quantity'] ?? 0);
        });

        if ($totalPuntos <= 0) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'El carrito no tiene puntos válidos para canjear.');
        }

        try {
            // 3) Ejecutar canje real (transaccional dentro del service)
            $redencion = $redemptionService->redimir(
                distributorId: $distributor->id,
                direccionId: (int) $request->direccion_id,
                puntosSolicitados: $totalPuntos,
                descripcion: 'Canje desde checkout'
            );

            foreach ($cart as $item) {
                RedencionProducto::create([
                    'redencion_id'   => $redencion->id,
                    'product_id'     => $item['product_id'],
                    'cantidad'       => $item['quantity'], // cajas
                    'puntos_unitarios' => $item['points'],
                    'puntos_total'   => $item['points'] * $item['quantity'],
                ]);
            }

            // 4) Limpiar carrito SOLO si fue exitoso
            session()->forget('cart');

            // 5) Redirigir a confirmación
            return redirect()
                ->route('distribuidores.checkout')
                ->with('success', 'Canje realizado con éxito. Tu pedido ha sido registrado.');
        } catch (\Throwable $e) {
            // Manejo seguro de errores (no exponemos detalles sensibles)
            report($e);

            return back()
                ->withInput()
                ->with('error', $e->getMessage() ?: 'No fue posible completar el canje.');
        }
    }
}
