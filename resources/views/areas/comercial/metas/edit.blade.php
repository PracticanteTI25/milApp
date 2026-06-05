@extends('layouts.admin')

@section('title', 'Editar meta')

@section('content')
<h1>Meta mensual</h1>

<p>
    <strong>Distribuidora:</strong> {{ $distributor->name }} <br>
    <strong>Periodo:</strong> {{ $currentMonth }}/{{ $currentYear }}
</p>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

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
            min="1"
            placeholder="Ej: 30000000">
    </div>

    <div class="mt-3 d-flex gap-2">
        <!-- GUARDAR -->
        <button class="btn btn-success">
            Guardar meta
        </button>

        <!-- ELIMINAR CON CONFIRMACIÓN -->
        @if($goal->exists)
        <button
            type="submit"
            name="goal_amount"
            value=""
            class="btn btn-danger"
            onclick="return confirm('¿Estás segura de eliminar la meta mensual? Esta acción no se puede deshacer.')">
            Quitar meta
        </button>
        @endif
    </div>

</form>
@endsection