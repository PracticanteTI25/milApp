@extends('layouts.admin')

@section('title', 'Editar stock')

@section('content')
<h1>Editar stock</h1>

<form method="POST" action="{{ route('comercial.stock.update', $product) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Producto</label>
        <input
            class="form-control"
            value="{{ $product->name }}"
            disabled>
    </div>

    <div class="form-group">
        <label>Stock disponible</label>
        <input
            type="number"
            name="stock"
            class="form-control"
            min="0"
            value="{{ old('stock', $product->stock ?? 0) }}"
            required>
    </div>

    <button class="btn btn-success">
        Guardar stock
    </button>

    <a href="{{ route('comercial.stock.index') }}"
        class="btn btn-secondary ml-2">
        Cancelar
    </a>
</form>
@endsection