<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\BolsaPuntos;
use App\Models\Distributor;
use App\Models\KardexPuntos;
use App\Models\DistributorMonthlyGoal;
use App\Models\PointSetting;

class CloseMonthlyPoints extends Command
{
    // nombre del comando
    protected $signature = 'points:close-month';

    // descripcion
    protected $description = 'Cierra el mes de puntos: habilita congelados y vence puntos según reglas';

    public function handle(): int
    {
        // Fecha de ejecución 
        $today = Carbon::today();

        // Mes a evaluar = mes inmediatamente anterior
        $monthToEvaluate = $today->copy()->subMonth()->startOfMonth();

        //los meses se obtienen desde el panel
        $settings = PointSetting::current();
        $expirationMonths = $settings->expiration_months;


        $this->info('Cierre de puntos - ventana de vencimiento: ' . $expirationMonths . ' meses');

        // Recorremos todos los distribuidores (procesa de a 100 para no explotar la memoria)
        Distributor::chunk(100, function ($distributors) use (
            $monthToEvaluate,
            $today,
            $expirationMonths
        ) {
            foreach ($distributors as $distributor) {

                $this->processDistributor(
                    $distributor,
                    $monthToEvaluate,
                    $today,
                    $expirationMonths
                );
            }
        });

        $this->info('Cierre mensual finalizado.');

        return Command::SUCCESS;
    }

    // Procesa cierre de puntos para un distribuidor
    private function processDistributor(
        Distributor $distributor,
        Carbon $monthToEvaluate,
        Carbon $today,
        int $expirationMonths
    ): void {

        //busca la meta del mes
        $goal = DistributorMonthlyGoal::where('distributor_id', $distributor->id)
            ->where('year', $monthToEvaluate->year)
            ->where('month', $monthToEvaluate->month)
            ->first();

        $goalMet = $goal && $goal->goal_amount > 0; // aquí luego irá lógica real de ventas

        if ($goalMet) {

            $bag = BolsaPuntos::where('distributor_id', $distributor->id)
                ->where('mes', $monthToEvaluate)
                ->first();

            if ($bag) {
                $congelados = $bag->puntos_generados - $bag->puntos_disponibles;

                if ($congelados > 0) {
                    $bag->increment('puntos_disponibles', $congelados);

                    KardexPuntos::create([
                        'distributor_id' => $distributor->id,
                        'bolsa_id' => $bag->id,
                        'tipo' => 'habilitacion',
                        'impacto' => 'suma_habilitada',
                        'puntos' => $congelados,
                        'descripcion' => 'Liberación de puntos por cierre mensual',
                        'fecha' => $today,
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
