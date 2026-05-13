@extends('layouts.admin')

@section('title', 'Configuración de puntos')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Configuración del sistema de puntos</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">

            <h5 class="card-title">Vencimiento de puntos</h5>

            <br>
            <p class="text-muted">
                Define cuántos meses después de ser otorgados vencen los puntos.
                Esta regla aplica de forma global a todo el sistema.
            </p>

            <form method="POST" action="{{ route('admin.puntos.configuracion.update') }}">
                @csrf

                <div class="form-group">
                    <label for="expiration_months">Meses antes de vencer</label>
                    <input
                        type="number"
                        id="expiration_months"
                        name="expiration_months"
                        class="form-control"
                        value="{{ old('expiration_months', $settings->expiration_months) }}"
                        min="1"
                        max="36"
                        required>
                    <small class="form-text text-muted">
                        Ejemplo: si ingresas <b>12</b>, los puntos vencerán a los 12 meses de haber sido otorgados.
                    </small>
                </div>

                <button class="btn btn-primary mt-3">
                    Guardar configuración
                </button>
            </form>

        </div>
    </div>

</div>
@endsection