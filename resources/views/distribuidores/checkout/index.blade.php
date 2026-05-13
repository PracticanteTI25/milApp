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

    {{-- =========================
        DIRECCIÓN DE ENTREGA
       ========================= --}}
    <div class="card">
        <h3>Dirección de entrega</h3>

        <p class="text-muted">
            Selecciona la dirección donde se enviará el pedido.
        </p>

        {{-- Placeholder: luego será listado desde API --}}
        <div class="alert alert-info">
            Las direcciones se cargarán desde la API de distribuidoras.
        </div>
    </div>

    {{-- =========================
        RESUMEN DEL CARRITO
       ========================= --}}
    <div class="card">
        <h3>Resumen del pedido</h3>

        <ul>
            @foreach($cart as $item)
            <li>
                {{ $item['name'] }}
                —
                {{ $item['quantity'] }} cajas
            </li>
            @endforeach
        </ul>
    </div>

    {{-- =========================
        CONFIRMACIÓN
       ========================= --}}
    <div class="distribuidores-checkout">
        <div class="mt-4">
            <form method="POST" action="{{ route('distribuidores.checkout.confirm') }}">
                @csrf

                {{-- Dirección seleccionada (placeholder) --}}
                <input type="hidden" name="direccion_id" value="1">

                <div class="form-check mb-3">
                    <label>
                        <input type="checkbox" name="confirmar" value="1">
                        Confirmo que deseo realizar este canje
                    </label>
                </div>
                <br>

                <button type="submit" class="btn btn-primary">
                    Confirmar canje
                </button>
            </form>
        </div>
    </div>


</div>
@endsection