<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RedemptionService
{
    /**
     * Procesa una redención completa a partir del carrito.
     *
     * @param int   $distributorId
     * @param array $cartItems
     *
     * @return Order
     */
    public function redeem(int $distributorId, array $cartItems): Order
    {
        if (empty($cartItems)) {
            throw new RuntimeException('El carrito está vacío');
        }

        return DB::transaction(function () use ($distributorId, $cartItems) {

            $totalPoints = 0;
            $products = [];

            /**
             *  Revalidar productos y calcular puntos
             */
            foreach ($cartItems as $item) {

                $product = Product::with('currentPrice')  //precio actual
                    ->where('id', $item['product_id'])
                    ->where('active', true)  //productos activos
                    ->lockForUpdate() // evita que dos personas esten comprando el mismo stock al mismo tiempo
                    ->firstOrFail();

                if (!$product->currentPrice) {
                    throw new RuntimeException(
                        "El producto {$product->name} no tiene precio vigente"
                    );
                }

                if ($product->stock < $item['quantity']) {
                    throw new RuntimeException(
                        "Stock insuficiente para {$product->name}."
                    );
                }

                //calcular puntos por producto y sumar el total
                $linePoints = $product->currentPrice->points * $item['quantity'];
                $totalPoints += $linePoints;

                //guarda info limpia para usar despues 
                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'points' => $product->currentPrice->points,
                ];
            }

            /**
             *  Descontar puntos (servicio central)
             */
            app(PointsService::class)->debitForRedemption(
                distributorId: $distributorId,
                amount: $totalPoints
            );

            /**
             *  Crear pedido
             */
            $order = Order::create([
                'distributor_id' => $distributorId,
                'total_points' => $totalPoints,
                'status' => 'pendiente',
            ]);

            /**
             *  Crear ítems y descontar stock
             */
            foreach ($products as $row) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $row['product']->id,
                    'points' => $row['points'],
                    'quantity' => $row['quantity'],
                ]);

                // Descontar stock
                $row['product']->decrement('stock', $row['quantity']);
            }

            /**
             * Limpiar carrito
             */
            session()->forget('cart');

            return $order;
        });
    }
}
