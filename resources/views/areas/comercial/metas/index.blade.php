@extends('layouts.admin')

@section('title', 'Metas mensuales')

@section('content')
<h1>Metas mensuales de distribuidoras</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Distribuidora</th>
            <th>Meta {{ $currentMonth }}/{{ $currentYear }}</th>
            <th>Ventas</th>
            <th>% Cumplimiento</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach($distributors as $distributor)
        @php
        $goal = $distributor->monthlyGoals->first();

        // evitar error si no hay meta
        if ($goal) {
        $sale = $goal->sales->first();
        $achieved = $sale ? $sale->achieved_amount : 0;

        $percentage = $goal->goal_amount > 0
        ? min(100, ($achieved / $goal->goal_amount) * 100)
        : 0;
        } else {
        $achieved = 0;
        $percentage = 0;
        }
        @endphp

        <tr>
            <td>{{ $distributor->name }}</td>

            <td>
                @if($goal)
                <strong>
                    ${{ number_format($goal->goal_amount, 0, ',', '.') }}
                </strong>
                @else
                <span class="text-muted">Sin meta</span>
                @endif
            </td>

            <td>
                ${{ number_format($achieved, 0, ',', '.') }}
            </td>

            <td>
                {{ number_format($percentage, 2) }}%

                @if($percentage >= 100)
                <span class="badge bg-success">Cumplido</span>
                @elseif($percentage >= 50)
                <span class="badge bg-warning">En proceso</span>
                @else
                <span class="badge bg-danger">Bajo</span>
                @endif
            </td>

            <td>
                <a href="{{ route('comercial.metas.edit', $distributor) }}"
                    class="btn btn-sm btn-primary">
                    Editar meta
                </a>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
@endsection