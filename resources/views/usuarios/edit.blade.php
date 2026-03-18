@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop
@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
<h1 class="body colorgris">Editar Usuario</h1>
@stop

@section('content')

<form action="/usuarios/{{ $usuario['id'] }}" method="POST" class="login-box">
    @csrf
    @method('PUT')

    <input type="text" name="nombre" value="{{ $usuario['nombre'] }}" class="form-control mb-2 input-group" required>

    <input type="text" name="apellido" value="{{ $usuario['apellido'] }}" class="form-control mb-2 input-group" required>

    <input type="email" name="correo" value="{{ $usuario['correo'] }}" class="form-control mb-2 input-group" required>

    <select name="rol" class="form-control mb-2 input-group" required>
        <option {{ $usuario->rol == 'Admin' ? 'selected' : '' }}>Admin</option>
        <option {{ $usuario->rol == 'Directivo' ? 'selected' : '' }}>Directivo</option>
        <option {{ $usuario->rol == 'Marketing' ? 'selected' : '' }}>Marketing</option>
        <option {{ $usuario->rol == 'Comercial' ? 'selected' : '' }}>Comercial</option>
    </select>

    <!-- CONTRASEÑA OPCIONAL -->
    <input type="password" name="password" placeholder="Nueva contraseña (opcional)" class="form-control mb-2 input-group">

    <button class="btn btn-primary">Actualizar</button>
</form>

@stop