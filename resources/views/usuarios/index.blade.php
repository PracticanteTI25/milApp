@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
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

<div class="card">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover colorgris">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th class="d-none d-md-table-cell">Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($usuarios as $u)
                    <tr>
                        <td>{{ $u->nombre }}</td>
                        <td>{{ $u->apellido }}</td>
                        <td class="d-none d-md-table-cell">{{ $u->correo }}</td>
                        <td>{{ $u->rol }}</td>

                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <a href="/usuarios/{{ $u['id'] }}/edit" class="btn btn-amarillo btn-sm mb-1">Editar</a>

                                <form action="/usuarios/{{ $u['id'] }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('¿Seguro?')" class="btn btn-naranja btn-sm mb-1">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@stop