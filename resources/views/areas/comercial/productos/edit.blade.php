@extends('layouts.admin')

@section('title', 'Editar producto')

@section('content_header')
<h1 class="colorgris body">Editar producto</h1>
@stop

@section('content')

<form action="{{ route('comercial.productos.update', $product) }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card mt-3">
        <div class="card-body">

            <div class="form-group">
                <label>Nombre</label> 
                <input name="name" class="form-control" required
                       value="{{ old('name', $product->name) }}">
            </div>

            <div class="form-group">
                <label>Presentación</label>
                <input name="presentation" class="form-control"
                       value="{{ old('presentation', $product->presentation) }}">
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Precio en puntos (vigente)</label>
                <input name="points" type="number" min="1" class="form-control" required
                       value="{{ old('points', optional($product->currentPrice)->points) }}">
                <small class="text-muted">
                    Si cambias el valor, se guardará el histórico automáticamente.
                </small>
            </div>

            <div class="form-group">
                <label>Stock</label>
                <input name="stock" type="number" min="0" class="form-control" required
                       value="{{ old('stock', $product->stock) }}">
            </div>

            <div class="form-group">
                <label>Imagen (opcional)</label>
                @if($product->image_path)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$product->image_path) }}" 
                             alt="Imagen"
                             style="max-height:120px;">
                    </div>
                @endif
                <input name="image" type="file" class="form-control-file" accept="image/*">
            </div>

            <input type="hidden" name="active" value="0">
            <div class="form-group form-check">
                <input type="checkbox" 
                       name="active" 
                       value="1" 
                       class="form-check-input" 
                       id="active"
                       {{ old('active', $product->active) ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Activo</label>
            </div>

            <button class="btn btn-primary">Guardar cambios</button>

        </div>
    </div>
</form>

@endsection