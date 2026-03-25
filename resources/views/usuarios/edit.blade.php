@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
@stop


@section('content_header')
<h1 class="body colorgris text-center text-md-left">Editar Usuario</h1>
@stop

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="/usuarios/{{ $usuario['id'] }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <input type="text" name="nombre" value="{{ $usuario['nombre'] }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="apellido" value="{{ $usuario['apellido'] }}" class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <input type="email" name="correo" value="{{ $usuario['correo'] }}" class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <select name="rol" class="form-control" required>
                            <option {{ $usuario->rol == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option {{ $usuario->rol == 'Directivo' ? 'selected' : '' }}>Directivo</option>
                            <option {{ $usuario->rol == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option {{ $usuario->rol == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="Nueva contraseña (opcional)"
                            class="form-control">
                    </div>

                    <button class="btn btn-primary btn-block">Actualizar</button>

                </form>

            </div>
        </div>

    </div>
</div>

@stop