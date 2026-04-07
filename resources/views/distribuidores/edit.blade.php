@extends('layouts.admin')

@section('title', 'Editar distribuidora')

@section('content')
    <h1 class="mb-3">Editar distribuidora</h1>

    <form action="{{ route('comercial.distribuidores.update', $distributor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h5>Datos de cuenta</h5>

        <div class="form-group">
            <label>Nombre completo</label>
            <input name="name" class="form-control" required value="{{ old('name', $distributor->name) }}">
        </div>

        <div class="form-group">
            <label>Correo (usuario)</label>
            <input name="email" type="email" class="form-control" required value="{{ old('email', $distributor->email) }}">
        </div>

        <div class="form-group">
            <label>Contraseña (opcional: solo si deseas cambiarla)</label>
            <input name="password" type="password" class="form-control" placeholder="Dejar vacío para no cambiar">

            <small class="form-text text-muted">
                Si se ingresa, debe tener al menos 8 caracteres, una mayúscula y un número.
            </small>

        </div>

        <input type="hidden" name="active" value="0">

        <div class="form-group form-check">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" {{ old('active', $distributor->active) ? 'checked' : '' }}>
            <label class="form-check-label" for="active">Activo</label>
        </div>



        <hr>
        <h5>Dirección de envío</h5>

        @php $a = $distributor->address; @endphp

        <div class="form-group">
            <label>País/Región</label>
            <input name="country" class="form-control" required
                value="{{ old('country', optional($a)->country ?? 'Colombia') }}">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input name="address_line1" class="form-control" required
                value="{{ old('address_line1', optional($a)->address_line1) }}">
        </div>

        <div class="form-group">
            <label>Apartamento / habitación / etc. (opcional)</label>
            <input name="address_line2" class="form-control"
                value="{{ old('address_line2', optional($a)->address_line2) }}">
        </div>

        <div class="form-group">
            <label>Ciudad</label>
            <input name="city" class="form-control" required value="{{ old('city', optional($a)->city) }}">
        </div>

        <div class="form-group">
            <label>Departamento</label>
            <input name="state" class="form-control" required value="{{ old('state', optional($a)->state) }}">
        </div>

        <div class="form-group">
            <label>Código postal (opcional)</label>
            <input name="postal_code" class="form-control" value="{{ old('postal_code', optional($a)->postal_code) }}">
        </div>

        <div class="form-group">
            <label>Teléfono (opcional)</label>
            <input name="phone" class="form-control" value="{{ old('phone', optional($a)->phone) }}">
        </div>

        <button class="btn btn-primary">Guardar cambios</button>
    </form>
@endsection