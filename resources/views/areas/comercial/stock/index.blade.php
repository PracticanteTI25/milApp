@extends('layouts.admin')

@section('title', 'Gestión de stock')

@section('content')
<h1>Gestión de stock de productos</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 70px;">Imagen</th>
            <th>Producto</th>
            <th>Stock actual</th>
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
            <td>{{ $product->stock ?? 0 }}</td>
            <td>{{ $product->active ? 'Sí' : 'No' }}</td>
            <td>
                <a href="{{ route('comercial.stock.edit', $product) }}" class="btn btn-sm btn-primary">
                    Editar stock
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection