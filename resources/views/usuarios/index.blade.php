@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop
@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
         {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
<h1 class="colorgris body">Gestión de Usuarios</h1>
@stop

@section('content')

<a href="/usuarios/create" class="btn btn-primary mb-3">Nuevo usuario</a>

<table class="table table-bordered colorgris">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach($usuarios as $u)
            <tr>
                <td>{{ $u->nombre }}</td>
                <td>{{ $u->apellido }}</td>
                <td>{{ $u->correo }}</td>
                <td>{{ $u->rol }}</td>
                <td>
                    <a href="/usuarios/{{ $u['id'] }}/edit" class="btn btn-amarillo">Editar</a>

                    <form action="/usuarios/{{ $u['id'] }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('¿Seguro que deseas eliminar este usuario?')"
                            class="btn btn-naranja">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@stop