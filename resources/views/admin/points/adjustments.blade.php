@extends('layouts.admin')

@section('title', 'Ajustes manuales de puntos')

@section('content')
<h1 class="mb-4">Ajustes manuales de puntos</h1>

{{-- Mensaje de éxito --}}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Errores de validación --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- FORMULARIO --}}
<form action="{{ route('admin.puntos.ajustes.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Distribuidora</label>
        <select name="distributor_id" class="form-control" required>
            <option value="">Seleccione una distribuidora</option>
            @foreach($distributors as $d)
            <option value="{{ $d->id }}" {{ old('distributor_id') == $d->id ? 'selected' : '' }}>
                {{ $d->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Tipo de ajuste</label>
        <select name="type" id="type" class="form-control" required>
            <option value="add" {{ old('type') == 'add' ? 'selected' : '' }}>Sumar puntos</option>
            <option value="subtract" {{ old('type') == 'subtract' ? 'selected' : '' }}>Restar puntos</option>
        </select>
    </div>

    <div class="form-group" id="state-group">
        <label>Estado inicial (solo suma)</label>
        <select name="initial_state" id="initial_state" class="form-control">
            <option value="habilitado" {{ old('initial_state') == 'habilitado' ? 'selected' : '' }}>Habilitado</option>
            <option value="congelado" {{ old('initial_state') == 'congelado' ? 'selected' : '' }}>Congelado</option>
        </select>
    </div>

    <div class="form-group">
        <label>Puntos</label>
        <input
            type="number"
            name="points"
            class="form-control"
            min="1"
            value="{{ old('points') }}"
            required>
    </div>

    <div class="form-group">
        <label>Motivo del ajuste</label>
        <textarea
            name="reason"
            class="form-control"
            required>{{ old('reason') }}</textarea>
    </div>

    <button class="btn btn-primary mt-3">Aplicar ajuste</button>
</form>

@push('scripts')
<script>
    const typeSelect = document.getElementById('type');
    const stateGroup = document.getElementById('state-group');
    const initialState = document.getElementById('initial_state');

    function toggleState() {
        if (typeSelect.value === 'add') {
            stateGroup.style.display = 'block';
            initialState.required = true;
        } else {
            stateGroup.style.display = 'none';
            initialState.required = false;
        }
    }

    // Ejecutar al cargar
    toggleState();

    // Ejecutar al cambiar
    typeSelect.addEventListener('change', toggleState);
</script>
@endpush

@endsection