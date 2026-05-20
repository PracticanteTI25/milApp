@extends('layouts.app')

@section('title', 'Confirmar canje')

{{-- CSS base de distribuidores --}}
@section('css')
<link rel="stylesheet" href="{{ asset('css/distribuidores.css') }}">
@endsection

@include('distribuidores.partials.navbar')

@section('content')
<div class="w">

    <h1>Confirmar canje</h1>

    @if ($errors->any())
    <div class="alert alert-danger mb-3">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- =========================
        DATOS DE LA DISTRIBUIDORA
       ========================= --}}
    <div class="card">
        <h3>Datos del vendedor</h3>

        <p>
            <strong>Nombre:</strong>
            {{ $distributor->name ?? '—' }}
        </p>

        <p>
            <strong>NIT:</strong>
            {{ $nit ?? 'Se cargará automáticamente' }}
        </p>

        <p>
            <strong>Teléfono:</strong>
            {{ $telefono ?? 'Se cargará automáticamente' }}
        </p>

        <p class="text-muted">
            Estos datos se cargarán automáticamente desde el sistema.
        </p>
    </div>


    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    {{-- =========================
        FORMULARIO COMPLETO
       ========================= --}}
    <div class="distribuidores-checkout">
        <div class="mt-4">

            <form method="POST" action="{{ route('distribuidores.checkout.confirm') }}">
                @csrf

                {{-- =========================
                    DIRECCIÓN DE ENTREGA
                   ========================= --}}
                <div class="card">
                    <h3>Dirección de entrega</h3>

                    <p class="text-muted">
                        Selecciona la dirección donde se enviará el pedido.
                    </p>

                    @if(isset($direcciones) && count($direcciones))
                    <div class="mb-3">
                        <select name="direccion_id" class="form-select" required>
                            @foreach($direcciones as $direccion)
                            <option value="{{ $direccion->id }}"
                                {{ old('direccion_id', $direccion->is_default ? $direccion->id : null) == $direccion->id ? 'selected' : '' }}>
                                {{ $direccion->address_line1 }} — {{ $direccion->city }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="alert alert-danger">
                        No tienes direcciones registradas. Por favor contacta al administrador.
                    </div>
                    @endif
                </div>

                {{-- =========================
                    RESUMEN DEL CARRITO
                   ========================= --}}
                <div class="card">
                    <h3>Resumen del pedido</h3>

                    <ul>
                        @foreach($cart as $item)
                        <li>
                            {{ $item['name'] }} — {{ $item['quantity'] }} cajas
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- =========================
                    CONFIRMACIÓN
                   ========================= --}}
                <div class="form-check mb-3">
                    <input
                        type="checkbox"
                        name="confirmar"
                        value="1"
                        id="confirmar"
                        class="form-check-input"
                        {{ old('confirmar') ? 'checked' : '' }}
                        required>
                    <label class="form-check-label" for="confirmar">
                        Confirmo que deseo realizar este canje
                    </label>
                </div>

                @error('confirmar')
                <div class="alert alert-danger mt-2">
                    {{ $message }}
                </div>
                @enderror

                <button type="submit" class="btn btn-primary">
                    Confirmar canje
                </button>

            </form>

        </div>
    </div>

</div>
@endsection