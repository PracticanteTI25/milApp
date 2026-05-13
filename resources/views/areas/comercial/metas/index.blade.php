@extends('layouts.admin')

@section('title', 'Metas mensuales')

@section('content')
<h1>Metas mensuales de distribuidoras</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Distribuidora</th>
            <th>Meta {{ $currentMonth }}/{{ $currentYear }}</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($distributors as $distributor)
        @php
        $goal = $distributor->monthlyGoals->first();
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