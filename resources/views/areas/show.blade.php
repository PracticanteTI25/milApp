@extends('layouts.admin')

@section('title', 'Área')

@section('content')
    <h1 class="colorgris body text-capitalize">Área: {{ str_replace('_', ' ', $slug) }}</h1>

    @if($slug === 'comercial')
        <div class="mt-3">

            {{-- Registro de distribuidoras --}}
            @if (Route::has('distribuidores.index'))
                <a href="{{ route('distribuidores.index') }}" class="btn btn-primary mr-2">
                    Registro de distribuidoras
                </a>
            @endif

            {{-- Asignación de puntos --}}
            @if (Route::has('comercial.puntos.index'))
                <a href="{{ route('comercial.puntos.index') }}" class="btn btn-primary mr-2">
                    Asignación de puntos
                </a>
            @endif

            {{-- Gestión de productos --}}
            @if (Route::has('comercial.productos.index'))
                <a href="{{ route('comercial.productos.index') }}" class="btn btn-primary mr-2">
                    Gestión de productos
                </a>
            @endif

            @if (
                    !Route::has('distribuidores.index') &&
                    !Route::has('comercial.puntos.index') &&
                    !Route::has('comercial.productos.index')
                )
                <div class="alert alert-info mt-3">
                    Este módulo está en construcción. Ya tienes acceso al área
                    <b>{{ str_replace('_', ' ', $slug) }}</b>.
                </div>
            @endif

        </div>

    @elseif($slug === 'logistica_distribucion')
        <div class="mt-3">

            {{-- Pedidos --}}
            @if (Route::has('logistica.pedidos.index'))
                <a href="{{ route('logistica.pedidos.index') }}" class="btn btn-primary">
                    Pedidos
                </a>
            @endif

            @if (!Route::has('logistica.pedidos.index'))
                <div class="alert alert-info mt-3">
                    Este módulo está en construcción. Ya tienes acceso al área
                    <b>{{ str_replace('_', ' ', $slug) }}</b>.
                </div>
            @endif

        </div>


    @else
        <div class="card mt-3">
            <div class="card-body">
                <p class="mb-0">
                    Este módulo está en construcción. Ya tienes acceso al área
                    <b>{{ str_replace('_', ' ', $slug) }}</b>.
                </p>
            </div>
        </div>
    @endif

@endsection