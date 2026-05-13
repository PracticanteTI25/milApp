@extends('layouts.admin')

@section('title', 'Editar meta')

@section('content')
<h1>Meta mensual</h1>

<p>
    <strong>Distribuidora:</strong> {{ $distributor->name }} <br>
    <strong>Periodo:</strong> {{ $currentMonth }}/{{ $currentYear }}
</p>

<form method="POST" action="{{ route('comercial.metas.update', $distributor) }}">
    @csrf

    <div class="form-group">
        <label>Meta mensual ($)</label>
        <input
            type="number"
            step="0.01"
            name="goal_amount"
            class="form-control"
            value="{{ old('goal_amount', $goal->goal_amount) }}"
            required>
    </div>

    <button class="btn btn-success">Guardar meta</button>
</form>
@endsection