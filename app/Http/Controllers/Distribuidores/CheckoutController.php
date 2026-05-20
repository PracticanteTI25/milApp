<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Distribuidores\CheckoutConfirmRequest;
use App\Services\RedemptionService;
use App\Models\RedencionProducto;
use App\Models\Redencion;
use App\Services\CartService;
use App\Models\DistributorAddress;
use App\Models\Product;

class CheckoutController extends Controller
{
    // Muestra la pantalla de checkout de canje 
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

        $direcciones = DistributorAddress::where('distributor_id', $distributor->id)
            ->orderByDesc('is_default')
            ->get();


        // Si no tiene direcciones, bloquear
        if ($direcciones->isEmpty()) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'No tienes direcciones registradas. Contacta al administrador.');
        }

        return view('distribuidores.checkout.index', [
            'distributor' => $distributor,
            'cart' => $cart,

            // TEMPORAL: direcciones vendrán de BD / API luego
            'direcciones' => $direcciones,

            // Estos datos luego vendrán de la API
            'nit' => null,
            'telefono' => null,
        ]);
    }

    // Confirmación del canje 

    public function confirm(
        CheckoutConfirmRequest $request,
        RedemptionService $redemptionService,
        CartService $cartService
    ) {
        $distributor = auth('distributor')->user();

        // Obtener carrito
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'El carrito está vacío.');
        }

        // Calcular puntos reales desde el CartService
        $totalPuntos = $cartService->totalPoints();

        if ($totalPuntos <= 0) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'El carrito no tiene puntos válidos para canjear.');
        }

        try {
            // Ejecutar canje real
            $redencion = $redemptionService->redimir(
                distributorId: $distributor->id,
                direccionId: (int) $request->direccion_id,
                puntosSolicitados: $totalPuntos,
                descripcion: 'Canje desde checkout'
            );

            // Guardar productos canjeados
            foreach ($cart as $item) {
                RedencionProducto::create([
                    'redencion_id' => $redencion->id,
                    'product_id'   => $item['product_id'],
                    'cantidad'     => $item['quantity'],
                    // estos campos se pueden eliminar luego si se decide no guardar puntos por producto
                    'puntos_unitarios' => $item['points'] ?? null,
                    'puntos_total'     => isset($item['points'])
                        ? $item['points'] * $item['quantity']
                        : null,
                ]);

                // Descontar stock del producto
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product) {
                    throw new \Exception('Producto no encontrado.');
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception(
                        'Stock insuficiente para el producto: ' . $product->name
                    );
                }

                $product->decrement('stock', $item['quantity']);
            }

            // Limpiar carrito
            session()->forget('cart');

            //  Redirigir a confirmación
            return redirect()
                ->route('distribuidores.canje.confirmacion', $redencion->id)
                ->with('success', 'Canje realizado correctamente.');
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', $e->getMessage() ?: 'No fue posible completar el canje.');
        }
    }

    public function confirmacion(Redencion $redencion)
    {
        // Cargar relaciones necesarias para la vista
        $redencion->load([
            'productos.product',
        ]);

        // IMPORTANTE:
        // La vista espera la variable $order
        return view('distribuidores.checkout.confirmacion', [
            'order' => $redencion,
        ]);
    }
}
