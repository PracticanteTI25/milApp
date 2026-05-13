@extends('layouts.admin')

@section('title', 'Crear producto')

@section('content')
<h1>Crear producto de incentivos</h1>

<form method="POST" action="{{ route('financiera.productos.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- Nombre --}}
    <div class="form-group">
        <label>Nombre del producto</label>
        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name') }}"
            required>
    </div>

    {{-- Descripción --}}
    <div class="form-group">
        <label>Descripción</label>
        <textarea
            name="description"
            class="form-control">{{ old('description') }}</textarea>
    </div>

    {{-- Presentación --}}
    <div class="form-group">
        <label>Presentación</label>
        <input
            type="text"
            name="presentation"
            class="form-control"
            value="{{ old('presentation') }}"
            placeholder="Ej: Caja x 28 unidades">
    </div>

    {{-- Imagen --}}
    <div class="form-group">
        <label>Imagen del producto</label>
        <input
            type="file"
            name="image"
            class="form-control-file"
            accept="image/*">
        <small class="form-text text-muted">
            JPG o PNG. Recomendado: fondo blanco.
        </small>
    </div>

    {{-- Puntos por caja --}}
    <div class="form-group">
        <label>Puntos por caja</label>
        <input
            type="number"
            name="points"
            class="form-control"
            value="{{ old('points') }}"
            min="1"
            required>
    </div>

    {{-- Activo --}}
    <div class="form-check mb-3">
        <input
            type="checkbox"
            name="active"
            class="form-check-input"
            {{ old('active', true) ? 'checked' : '' }}>
        <label class="form-check-label">
            Producto activo
        </label>
    </div>

    <button class="btn btn-success">
        Guardar producto
    </button>

    <a href="{{ route('financiera.productos.index') }}"
        class="btn btn-secondary ml-2">
        Cancelar
    </a>
</form>
@endsection