@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
<h1>Productos de incentivos</h1>

<a href="{{ route('financiera.productos.create') }}" class="btn btn-primary mb-3">
    Crear producto
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 70px;">Imagen</th>
            <th>Nombre</th>
            <th>Puntos por caja</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td class="text-center align-middle">
                @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}"
                    alt="{{ $product->name }}"
                    style="width: 50px; height: 50px; border-radius: 6px; object-fit: contain;">
                @else
                <span class="text-muted">Sin imagen</span>
                @endif
            </td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->currentPrice->points ?? '—' }}</td>
            <td>{{ $product->active ? 'Sí' : 'No' }}</td>
            <td>
                <a href="{{ route('financiera.productos.edit', $product) }}" class="btn btn-sm btn-warning">
                    Editar
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection