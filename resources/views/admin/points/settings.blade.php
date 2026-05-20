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

            <h5 class="card-title">Configuración general</h5>

            <br>

            <p class="text-muted">
                Define las reglas globales del sistema de puntos.
            </p>

            <form method="POST" action="{{ route('admin.puntos.configuracion.update') }}">
                @csrf

                {{-- VENCIMIENTO DE PUNTOS --}}
                <div class="form-group mb-4">
                    <label for="expiration_months">Meses antes de vencer</label>
                    <input
                        type="number"
                        id="expiration_months"
                        name="expiration_months"
                        class="form-control"
                        value="{{ old('expiration_months', $pointSettings->expiration_months) }}"
                        min="1"
                        max="36"
                        required>
                    <small class="form-text text-muted">
                        Ejemplo: si ingresas <b>12</b>, los puntos vencerán a los 12 meses.
                    </small>
                </div>

                {{-- VALOR DEL PUNTO --}}
                <div class="form-group mb-4">
                    <label for="pesos_por_punto">Valor de 1 punto (COP)</label>
                    <input
                        type="number"
                        id="pesos_por_punto"
                        name="pesos_por_punto"
                        class="form-control"
                        value="{{ old('pesos_por_punto', $pesosPorPunto) }}"
                        min="1"
                        step="1"
                        required>
                    <small class="form-text text-muted">
                        Este valor es interno y no se muestra a distribuidores.
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