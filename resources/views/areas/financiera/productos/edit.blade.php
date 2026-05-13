@extends('layouts.admin')

@section('title', 'Editar producto')

@section('content')
<h1>Editar producto de incentivos</h1>

<form method="POST" action="{{ route('financiera.productos.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Nombre --}}
    <div class="form-group">
        <label>Nombre del producto</label>
        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $product->name) }}"
            required>
    </div>

    {{-- Descripción --}}
    <div class="form-group">
        <label>Descripción</label>
        <textarea
            name="description"
            class="form-control">{{ old('description', $product->description) }}</textarea>
    </div>

    {{-- Presentación --}}
    <div class="form-group">
        <label>Presentación</label>
        <input
            type="text"
            name="presentation"
            class="form-control"
            value="{{ old('presentation', $product->presentation) }}">
    </div>

    {{-- Imagen actual --}}
    @if($product->image_path)
    <div class="mb-3">
        <label>Imagen actual</label><br>
        <img
            src="{{ asset('storage/' . $product->image_path) }}"
            alt="Imagen del producto"
            style="max-height: 120px; border: 1px solid #ccc;">
    </div>
    @endif

    {{-- Nueva imagen --}}
    <div class="form-group">
        <label>Cambiar imagen</label>
        <input
            type="file"
            name="image"
            class="form-control-file"
            accept="image/*">
    </div>

    {{-- Puntos por caja --}}
    <div class="form-group">
        <label>Puntos por caja (precio vigente)</label>
        <input
            type="number"
            name="points"
            class="form-control"
            value="{{ old('points', $product->currentPrice->points ?? '') }}"
            min="1"
            required>
        <small class="form-text text-muted">
            Al guardar, el precio anterior se cerrará y se creará uno nuevo.
        </small>
    </div>

    {{-- Activo --}}
    <div class="form-check mb-3">
        <input
            type="checkbox"
            name="active"
            class="form-check-input"
            {{ old('active', $product->active) ? 'checked' : '' }}>
        <label class="form-check-label">
            Producto activo
        </label>
    </div>

    <button class="btn btn-success">
        Actualizar producto
    </button>

    <a href="{{ route('financiera.productos.index') }}"
        class="btn btn-secondary ml-2">
        Cancelar
    </a>
</form>
@endsection