@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
    <h1>Usuarios</h1>

    <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">
        Crear usuario
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Área</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->role->name ?? '-' }}</td>
                        <td>{{ $u->area->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('usuarios.edit', $u->id) }}" class="btn btn-sm btn-warning">Editar</a>

                            <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection