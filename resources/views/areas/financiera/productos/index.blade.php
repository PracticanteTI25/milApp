@extends('layouts.admin')

@section('title', 'Productos')

@section('content')

<h1>Productos de incentivos</h1>

{{-- BOTÓN CREACIÓN MANUAL --}}
<a href="{{ route('financiera.productos.create') }}" class="btn btn-primary mb-2">
    Crear producto manual
</a>

<small class="text-muted d-block mb-3">
    Los productos manuales sirven como respaldo si la sincronización externa no está disponible.
</small>

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
        @forelse($products as $product)
        <tr>
            {{-- IMAGEN --}}
            <td class="text-center align-middle">
                @if($product->image_path)
                <img
                    src="{{ url('files/' . $product->image_path) }}"
                    alt="{{ $product->name }}"
                    style="width: 50px; height: 50px; border-radius: 6px; object-fit: contain;">
                @else
                <span class="text-muted">Sin imagen</span>
                @endif
            </td>

            {{-- NOMBRE + ORIGEN --}}
            <td>
                <strong>{{ $product->name }}</strong>

                @if($product->source === 'manual')
                <span class="badge badge-secondary ml-2">
                    Manual
                </span>
                @elseif($product->source === 'api')
                <span class="badge badge-info ml-2">
                    Sincronizado
                </span>
                @endif
            </td>

            {{-- PUNTOS --}}
            <td>
                {{ optional($product->currentPrice)->points ?? '—' }}
            </td>

            {{-- ACTIVO --}}
            <td>
                {{ $product->active ? 'Sí' : 'No' }}
            </td>

            {{-- ACCIONES --}}
            <td>
                @if($product->source === 'api')
                <a href="{{ route('financiera.productos.edit', $product) }}"
                    class="btn btn-sm btn-info">
                    Editar precio y estado
                </a>
                @else
                <a href="{{ route('financiera.productos.edit', $product) }}"
                    class="btn btn-sm btn-warning">
                    Editar
                </a>
                @endif
            </td>

        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center text-muted">
                No hay productos registrados.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection