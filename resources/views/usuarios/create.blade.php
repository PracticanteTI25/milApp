@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
<h1 class="colorgris body">Crear Usuario</h1>
@stop

@section('content')

<form action="{{ route('usuarios.store') }}" method="POST" class="login-box">
    @csrf

    <input type="text" name="nombre" placeholder="Nombre" class="form-control mb-2 input-group" required>
    <input type="text" name="apellido" placeholder="Apellido" class="form-control mb-2 input-group" required>
    <input type="email" name="correo" placeholder="Correo" class="form-control mb-2 input-group" required>
    <select name="rol" class="form-control mb-2 input-group" required>
        <option value="">Seleccione rol</option>
        <option value="Admin">Admin</option>
        <option value="Directivo">Directivo</option>
        <option value="Marketing">Marketing</option>
        <option value="Comercial">Comercial</option>
    </select>
    <input type="password" name="password" placeholder="Contraseña" class="form-control mb-2 input-group" required>

    <button class="btn btn-primary">Guardar</button>
</form>

@stop