@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
    <h1>Productos</h1>

    {{ route('logistica.productos.create') }}
    + Nuevo producto
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Puntos</th>
                    <th>Stock</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ optional($p->currentPrice)->points ?? '-' }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>{{ $p->active ? 'Sí' : 'No' }}</td>
                        <td>
                            {{ route('logistica.productos.edit', $p->id) }}Editar</a>

                            {{ route('logistica.productos.destroy', $p->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection