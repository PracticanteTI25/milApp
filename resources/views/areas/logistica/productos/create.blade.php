@extends('layouts.admin')

@section('title', 'Crear producto')

@section('content_header')
<h1 class="colorgris body">Crear producto</h1>
@stop

@section('content')

    <form action="{{ route('logistica.productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mt-3">
            <div class="card-body">

                <div class="form-group">
                    <label>Nombre</label>
                    <input name="name" class="form-control" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label>Presentación (ej: Caja x 12)</label>
                    <input name="presentation" class="form-control" value="{{ old('presentation') }}">
                </div>

                <div class="form-group">
                    <label>Descripción (opcional)</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Precio en puntos</label>
                    <input name="points" type="number" min="1" class="form-control" required value="{{ old('points') }}">
                </div>

                <div class="form-group">
                    <label>Stock</label>
                    <input name="stock" type="number" min="0" class="form-control" required value="{{ old('stock', 0) }}">
                </div>

                <div class="form-group">
                    <label>Imagen (opcional)</label>
                    <input name="image" type="file" class="form-control-file" accept="image/*">
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" name="active" value="1" class="form-check-input" id="active" checked>
                    <label class="form-check-label" for="active">
                        Activo
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    Guardar
                </button>

            </div>
        </div>
    </form>

@endsection