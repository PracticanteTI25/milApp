<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\DistributorMonthlyGoal;
use Illuminate\Http\Request;

class DistributorGoalController extends Controller
{

    public function index()
    {
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        $distributors = Distributor::with([
            'monthlyGoals' => function ($query) use ($currentYear, $currentMonth) {

                $query->where('year', $currentYear)
                    ->where('month', $currentMonth)
                    ->with(['sales' => function ($query) use ($currentYear, $currentMonth) {
                        $query->where('year', $currentYear)
                            ->where('month', $currentMonth);
                    }]);
            }
        ])
            ->orderBy('name')
            ->get();

        return view('areas.comercial.metas.index', [
            'distributors' => $distributors,
            'currentYear'  => $currentYear,
            'currentMonth' => $currentMonth,
        ]);
    }

    public function edit(Distributor $distributor)
    {
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        //firstOrNew busca si una meta existe, si existe la carga, si no hay, te da una nueva para llenar el formulario
        $goal = DistributorMonthlyGoal::firstOrNew([
            'distributor_id' => $distributor->id,
            'year'  => $currentYear,
            'month' => $currentMonth,
        ]);

        //enviamos a la vista el distribuidor, la meta, el año y mes
        return view('areas.comercial.metas.edit', compact(
            'distributor',
            'goal',
            'currentYear',
            'currentMonth'
        ));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        $data = $request->validate([
            'goal_amount' => ['nullable', 'numeric', 'min:1'],   // ahora puede venir vacío
        ]);

        // eliminar meta (sin meta asignada)
        if (empty($request->goal_amount)) {

            DistributorMonthlyGoal::where('distributor_id', $distributor->id)
                ->where('year', $currentYear)
                ->where('month', $currentMonth)
                ->delete();

            return back()->with('success', 'Meta eliminada correctamente');
        }

        //si hay meta este mes, se actualiza, si no hay, se crea
        DistributorMonthlyGoal::updateOrCreate(
            [
                'distributor_id' => $distributor->id,
                'year'  => $currentYear,
                'month' => $currentMonth,
            ],
            [
                'goal_amount' => $request->goal_amount,
            ]
        );

        return redirect()
            ->route('comercial.metas.index')
            ->with('success', 'Meta mensual actualizada correctamente.');
    }
}
