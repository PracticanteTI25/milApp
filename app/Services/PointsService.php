<?php

namespace App\Services;

use App\Models\Distributor;
use App\Models\PointMovement;
use Illuminate\Support\Facades\DB;

/**
 * PointsService
 *
 * Servicio centralizado para manejar puntos.
 * Importante: centralizar aquí evita que “cada controlador haga su lógica”
 * y reduce errores en un sistema real.
 */
class PointsService
{
    /**
     * Sumar puntos manualmente (Comercial).
     *
     * @param int $distributorId
     * @param int $amount
     * @param string|null $comment
     * @param int|null $actorUserId  Usuario interno que asigna (auth()->id())
     */
    public function manualCredit(int $distributorId, int $amount, ?string $comment, ?int $actorUserId): void
    {
        if ($amount <= 0) {
            // Seguridad: no permitir valores inválidos.
            throw new \InvalidArgumentException('El valor a sumar debe ser mayor que cero.');
        }

        DB::transaction(function () use ($distributorId, $amount, $comment, $actorUserId) {

            /**
             * Bloqueo FOR UPDATE:
             * Evita condiciones de carrera:
             * - Dos personas actualizando puntos a la misma distribuidora al mismo tiempo.
             * Esto previene inconsistencias en saldo (muy importante en “vida real”).
             */
            $distributor = Distributor::where('id', $distributorId)
                ->lockForUpdate()
                ->firstOrFail();

            $newBalance = $distributor->points_balance + $amount;

            // Actualizar saldo en tabla principal (rápido de consultar)
            $distributor->points_balance = $newBalance;
            $distributor->save();

            // Guardar movimiento (historial / extracto)
            PointMovement::create([
                'distributor_id' => $distributor->id,
                'delta' => $amount,
                'balance_after' => $newBalance,
                'type' => 'manual_credit',
                'comment' => $comment,
                'created_by_user_id' => $actorUserId,
            ]);
        });
    }

    /**
     * Restar puntos manualmente (Comercial).
     * Útil si algún día necesitan corregir un saldo.
     */

    // esto previene errores en producción (no saldo negativo), y deja trazabilidad (OWASP: control y auditoría)
    
    public function manualDebit(int $distributorId, int $amount, ?string $comment, ?int $actorUserId): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('El valor a restar debe ser mayor que cero.');
        }

        DB::transaction(function () use ($distributorId, $amount, $comment, $actorUserId) {

            // 🔒 lockForUpdate evita inconsistencias si dos personas editan a la vez.
            $distributor = Distributor::where('id', $distributorId)
                ->lockForUpdate()
                ->firstOrFail();

            // ✅ No permitir saldo negativo
            if ($distributor->points_balance < $amount) {
                throw new \RuntimeException('No se puede restar más puntos de los que tiene la distribuidora.');
            }

            $newBalance = $distributor->points_balance - $amount;

            $distributor->points_balance = $newBalance;
            $distributor->save();

            // Historial (extracto)
            PointMovement::create([
                'distributor_id' => $distributor->id,
                'delta' => -$amount,
                'balance_after' => $newBalance,
                'type' => 'manual_debit',
                'comment' => $comment,
                'created_by_user_id' => $actorUserId,
            ]);
        });
    }

    /**
     * Restar puntos automáticamente por redención (pedido).
     *
     * @param int $distributorId
     * @param int $amount
     * @param int|null $orderId
     */
    public function debitForRedemption(int $distributorId, int $amount, ?int $orderId = null): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('El valor a descontar debe ser mayor que cero.');
        }

        DB::transaction(function () use ($distributorId, $amount, $orderId) {

            $distributor = Distributor::where('id', $distributorId)
                ->lockForUpdate()
                ->firstOrFail();

            // Bloqueo con mensaje claro: no permitir saldo negativo
            if ($distributor->points_balance < $amount) {
                throw new \RuntimeException('La distribuidora no tiene puntos suficientes para redimir.');
            }

            $newBalance = $distributor->points_balance - $amount;

            // Actualizar saldo y acumulado redimido
            $distributor->points_balance = $newBalance;
            $distributor->points_redeemed = $distributor->points_redeemed + $amount;
            $distributor->save();

            // Guardar movimiento negativo (extracto)
            PointMovement::create([
                'distributor_id' => $distributor->id,
                'delta' => -$amount,
                'balance_after' => $newBalance,
                'type' => 'redemption',
                'comment' => 'Redención por pedido',
                'created_by_user_id' => null, // lo hace el sistema
                'order_id' => $orderId,
            ]);
        });
    }
}