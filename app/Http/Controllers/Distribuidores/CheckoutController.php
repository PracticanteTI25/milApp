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
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $distributor = auth('distributor')->user();

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'No tienes productos en el carrito.');
        }

        $direcciones = DistributorAddress::where('distributor_id', $distributor->id)
            ->orderByDesc('is_default')
            ->get();

        if ($direcciones->isEmpty()) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'No tienes direcciones registradas. Contacta al administrador.');
        }

        return view('distribuidores.checkout.index', [
            'distributor' => $distributor,
            'cart' => $cart,
            'direcciones' => $direcciones,
            'nit' => null,
            'telefono' => null,
        ]);
    }

    public function confirm(
        CheckoutConfirmRequest $request,
        RedemptionService $redemptionService,
        CartService $cartService
    ) {

        $distributor = auth('distributor')->user();

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'El carrito está vacío.');
        }

        $totalPuntos = $cartService->totalPoints();

        if ($totalPuntos <= 0) {
            return redirect()
                ->route('distribuidores.catalogo')
                ->with('error', 'El carrito no tiene puntos válidos para canjear.');
        }

        // MOVIDO ARRIBA (ANTES DEL TRY)
        $direccion = DistributorAddress::where('id', $request->direccion_id)
            ->where('distributor_id', $distributor->id)
            ->firstOrFail();

        try {

            DB::transaction(function () use (
                $distributor,
                $direccion,
                $cart,
                $totalPuntos,
                $redemptionService
            ) {

                // Redimir puntos
                $redencion = $redemptionService->redimir(
                    distributorId: $distributor->id,
                    direccionId: $direccion->id,
                    puntosSolicitados: $totalPuntos,
                    descripcion: 'Canje desde checkout'
                );

                // Snapshot del pedido
                $redencion->update([
                    'document_snapshot'     => $distributor->document,
                    'nombre_snapshot'       => $distributor->name,
                    'direccion_snapshot'    => $direccion->address_line1,
                    'municipio_snapshot'    => $direccion->city,
                    'departamento_snapshot' => $direccion->state,
                    'telefono_snapshot'     => $direccion->phone,
                ]);

                // Productos + stock
                foreach ($cart as $item) {

                    RedencionProducto::create([
                        'redencion_id' => $redencion->id,
                        'product_id'   => $item['product_id'],
                        'cantidad'     => $item['quantity'],
                    ]);

                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception(
                            'Stock insuficiente para el producto: ' . $product->name
                        );
                    }

                    $product->decrement('stock', $item['quantity']);
                }

                // Limpiar carrito
                session()->forget('cart');

                // Guardar redención en sesión
                session(['redencion_id' => $redencion->id]);
            });

            return redirect()
                ->route('distribuidores.canje.confirmacion', session('redencion_id'))
                ->with('success', 'Canje realizado correctamente.');
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'general' => $e->getMessage() ?: 'No fue posible completar el canje.',
                ]);
        }
    }

    public function confirmacion(Redencion $redencion)
    {
        $redencion->load([
            'productos.product',
        ]);

        return view('distribuidores.checkout.confirmacion', [
            'order' => $redencion,
        ]);
    }
}
