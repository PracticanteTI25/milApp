@extends('layouts.admin')

distribuidora')

@section('content')
    <h1>Crear distribuidora</h1>

    <form action="{{ route('distribuidores.store') }}" method="POST">
        @csrf

        <h5>Datos de distribuidora</h5>

        <div class="form-group">
            <label>Nombre completo</label>
            <input name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Correo (usuario)</label>
            <input name="email" type="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input name="password" type="password" class="form-control" required>

            <small class="form-text text-muted">
                La contraseña debe tener al menos 8 caracteres, una letra mayúscula y un número.
            </small>

        </div>

        <hr>
        <h5>Dirección de envío</h5>

        <div class="form-group">
            <label>País/Región</label>
            <input name="country" class="form-control" value="Colombia" required>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input name="address_line1" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Apartamento / habitación / etc. (opcional)</label>
            <input name="address_line2" class="form-control">
        </div>

        <div class="form-group">
            <label>Ciudad</label>
            <input name="city" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Departamento</label>
            <input name="state" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Código postal (opcional)</label>
            <input name="postal_code" class="form-control">
        </div>

        <div class="form-group">
            <label>Teléfono (opcional)</label>
            <input name="phone" class="form-control">
        </div>

        <button class="btn btn-primary">Guardar</button>
    </form>
@endsection