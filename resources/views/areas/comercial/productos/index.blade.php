@extends('layouts.admin')

@section('title', 'Productos')

@section('content_header')
<h1 class="colorgris body">Productos</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <a href="{{ route('comercial.productos.create') }}" class="btn btn-primary">
        + Nuevo producto
    </a>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Puntos</th>
                    <th>Stock</th>
                    <th>Activo</th>
                    <th style="width:170px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>
                            {{ $p->name }}
                            @if($p->presentation)
                                <div class="text-muted small">{{ $p->presentation }}</div>
                            @endif
                        </td>
                        <td>{{ optional($p->currentPrice)->points ?? '-' }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>{{ $p->active ? 'Sí' : 'No' }}</td>
                        <td>
                            <!-- BOTÓN EDITAR -->
                            <a href="{{ route('comercial.productos.edit', $p) }}" class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <!-- FORM ELIMINAR -->
                            <form action="{{ route('comercial.productos.destroy', $p) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">
                                    Eliminar
                                </button>
                            </form>
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
    </div>

@endsection