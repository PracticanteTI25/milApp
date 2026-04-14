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
                <a href="{{ route('comercial.puntos.index') }}" class="btn btn-primary">
                    Asignación de puntos
                </a>
            @endif

            @if (!Route::has('distribuidores.index') && !Route::has('comercial.puntos.index'))
                <div class="alert alert-info mt-3">
                    Este módulo está en construcción. Ya tienes acceso al área
                    <b>{{ str_replace('_', ' ', $slug) }}</b>.
                </div>
            @endif

        </div>

    @elseif($slug === 'logistica_distribucion')
        <div class="mt-3">

            {{-- Gestión de productos --}}
            @if (Route::has('logistica.productos.index'))
                <a href="{{ route('logistica.productos.index') }}" class="btn btn-primary">
                    Gestión de productos
                </a>
            @endif

            @if (!Route::has('logistica.productos.index'))
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