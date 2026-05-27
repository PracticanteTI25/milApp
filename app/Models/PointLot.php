<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class PointLot extends Model
{
    use HasFactory;

    /**
     * Campos asignables masivamente.
     *
     * IMPORTANTE:
     * - Solo incluimos campos que se crean/controlan desde servicios.
     * - No exponemos nada sensible innecesariamente.
     */
    protected $fillable = [
        'distributor_id',
        'bolsa_id',
        'source',
        'points_initial',
        'points_remaining',
        'fecha_habilitacion',
        'fecha_vencimiento',
        'status',
    ];

    /**
     * Casts automáticos.
     *
     * Garantizan:
     * - fechas como Carbon
     * - valores consistentes en toda la app
     */
    protected $casts = [
        'fecha_habilitacion' => 'datetime',
        'fecha_vencimiento'  => 'datetime',
    ];

    /**
     * Distribuidor dueño de los puntos.
     */
    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Bolsa mensual a la que pertenece el lote.
     */
    public function bolsa()
    {
        return $this->belongsTo(BolsaPuntos::class, 'bolsa_id');
    }

    /**
     * ============================
     * Helpers de dominio (seguros)
     * ============================
     */

    /**
     * Indica si el lote sigue vivo.
     */
    public function isAlive(): bool
    {
        return $this->points_remaining > 0
            && in_array($this->status, ['disponible', 'congelado']);
    }

    /**
     * Indica si el lote ya está vencido.
     */
    public function isExpired(): bool
    {
        return $this->fecha_vencimiento !== null
            && $this->fecha_vencimiento->isPast()
            && $this->status !== 'vencido';
    }

    /**
     * Indica si el lote puede consumirse.
     */
    public function isConsumable(): bool
    {
        return $this->status === 'disponible'
            && $this->points_remaining > 0;
    }
}
