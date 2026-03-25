@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
@stop

@section('content_header')
<h1 class="colorgris body text-center text-md-left">Crear Usuario</h1>
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <input type="text" name="nombre" placeholder="Nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="apellido" placeholder="Apellido" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <input type="email" name="correo" placeholder="Correo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <select name="rol" class="form-control" required>
                            <option value="">Seleccione rol</option>
                            <option value="Admin">Admin</option>
                            <option value="Directivo">Directivo</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Comercial">Comercial</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="Contraseña" class="form-control" required>
                    </div>

                    <button class="btn btn-primary btn-block">Guardar</button>

                </form>

            </div>
        </div>

    </div>
</div>

@stop