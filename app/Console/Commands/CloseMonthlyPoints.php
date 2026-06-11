<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\BolsaPuntos;
use App\Models\Distributor;
use App\Models\KardexPuntos;
use App\Models\DistributorMonthlyGoal;
use App\Models\PointSetting;
use App\Models\Sale;
use App\Models\PointLot;

class CloseMonthlyPoints extends Command
{
    protected $signature = 'points:close-month';

    protected $description = 'Cierra el mes de puntos: habilita congelados y vence puntos según reglas';

    public function handle(): int
    {
        $today = Carbon::today();

        // Mes a evaluar = mes inmediatamente anterior
        // $monthToEvaluate = $today->copy()->subMonth()->startOfMonth();

        //  ESTE ES PARA PRUEBAS
        $monthToEvaluate = $today->copy()->startOfMonth();

        $settings = PointSetting::current();
        $expirationMonths = $settings->expiration_months;

        $this->info('Cierre de puntos - ventana de vencimiento: ' . $expirationMonths . ' meses');

        Distributor::chunk(100, function ($distributors) use (
            $monthToEvaluate,
            $today,
            $expirationMonths,
            $settings
        ) {
            foreach ($distributors as $distributor) {

                $this->processDistributor(
                    $distributor,
                    $monthToEvaluate,
                    $today,
                    $expirationMonths,
                    $settings
                );
            }
        });

        $this->info('Cierre mensual finalizado.');

        return Command::SUCCESS;
    }

    private function processDistributor(
        Distributor $distributor,
        Carbon $monthToEvaluate,
        Carbon $today,
        int $expirationMonths,
        $settings
    ): void {

        $goal = DistributorMonthlyGoal::where('distributor_id', $distributor->id)
            ->where('year', $monthToEvaluate->year)
            ->where('month', $monthToEvaluate->month)
            ->first();

        $venta = Sale::where('distributor_id', $distributor->id)
            ->where('year', $monthToEvaluate->year)
            ->where('month', $monthToEvaluate->month)
            ->first();

        $ventas = $venta ? $venta->achieved_amount : 0;

        $goalMet = $goal && $ventas >= $goal->goal_amount;

        // HABILITACIÓN DE PUNTOS
        if ($goalMet) {

            $bag = BolsaPuntos::where('distributor_id', $distributor->id)
                ->where('mes', $monthToEvaluate)
                ->first();

            if ($bag && $bag->estado !== 'habilitado') {

                $congelados = KardexPuntos::where('bolsa_id', $bag->id)
                    ->where(function ($q) {
                        // automáticos congelados
                        $q->where('tipo', 'generacion')

                            // manual congelado
                            ->orWhere(function ($q2) {
                                $q2->where('tipo', 'ajuste')
                                    ->where('impacto', 'suma_congelada');
                            });
                    })
                    ->sum('puntos');


                if ($congelados > 0) {

                    // liberar puntos
                    $bag->puntos_disponibles += $congelados;
                    $bag->estado = 'habilitado';
                    $bag->save();

                    // registrar en kardex
                    KardexPuntos::create([
                        'distributor_id' => $distributor->id,
                        'bolsa_id' => $bag->id,
                        'tipo' => 'habilitacion',
                        'impacto' => 'suma_habilitada',
                        'puntos' => $congelados,
                        'descripcion' => 'Liberación de puntos por cumplimiento de meta',
                        'fecha' => $today,
                    ]);

                    // CREAR LOTES

                    $settings = PointSetting::current();
                    $expirationMonths = $settings->expiration_months;


                    PointLot::create([
                        'distributor_id'     => $distributor->id,
                        'bolsa_id'           => $bag->id,
                        'source'             => 'generado',
                        'points_initial'     => $congelados,
                        'points_remaining'   => $congelados,
                        'fecha_habilitacion' => $today,
                        'fecha_vencimiento'  => $today->copy()->addMonths($expirationMonths),
                        'status'             => 'disponible',
                    ]);
                }
            }
        }

        // VENCIMIENTO
        $bags = BolsaPuntos::where('distributor_id', $distributor->id)->get();

        foreach ($bags as $bag) {

            $start = Carbon::parse($bag->mes)->addMonth()->startOfMonth();
            $expirationDate = $start->copy()->addMonths($expirationMonths);

            if ($today->greaterThanOrEqualTo($expirationDate)) {

                $congelados = $bag->puntos_generados - $bag->puntos_disponibles;

                if ($congelados > 0) {

                    $bag->decrement('puntos_generados', $congelados);

                    if ($bag->puntos_generados <= 0) {
                        $bag->estado = 'vencido';
                        $bag->save();
                    }

                    KardexPuntos::create([
                        'distributor_id' => $distributor->id,
                        'bolsa_id' => $bag->id,
                        'tipo' => 'vencimiento',
                        'impacto' => 'resta',
                        'puntos' => -$congelados,
                        'descripcion' => 'Vencimiento de puntos',
                        'fecha' => $today,
                    ]);
                }
            }
        }
    }
}
