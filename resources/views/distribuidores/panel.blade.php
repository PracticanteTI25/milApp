@extends('layouts.app')

@section('title', 'Panel de Distribuidores')

@section('content')
    <div class="dist-container">
        <div class="dist-card">

            {{-- CABECERA --}}
            <div class="mb-4">
                <h1 class="dist-title">Panel de Distribuidores</h1>

                <p class="text-muted">
                    Bienvenido al panel. Desde aquí podrás consultar el catálogo
                    y próximamente realizar canjes con tus puntos.
                </p>
            </div>

            {{-- ACCIÓN PRINCIPAL --}}
            <div class="dist-section mb-4">
                <h3 class="dist-section-title">
                    ¿Qué deseas hacer?
                </h3>

                <div class="dist-actions">
                    <a href="{{ route('distribuidores.catalogo') }}" class="dist-action">
                        <span class="dist-action-icon">🛍️</span>
                        <div class="dist-action-text">
                            <strong>Ver catálogo de productos</strong>
                            <small>Explora los productos disponibles para canje</small>
                        </div>
                    </a>
                </div>
            </div>

            {{-- PRÓXIMAMENTE --}}
            <div class="dist-section mb-4">
                <h3 class="dist-section-title text-muted">
                    Próximamente
                </h3>

                <button class="dist-action-disabled" disabled>
                    <span class="dist-action-icon">🎁</span>
                    <div class="dist-action-text">
                        <strong>Canjear puntos</strong>
                        <small>Funcionalidad en desarrollo</small>
                    </div>
                </button>
            </div>

            {{-- CERRAR SESIÓN --}}
            <form method="POST" action="{{ route('distribuidores.logout') }}">
                @csrf
                <button class="dist-btn dist-btn-secondary w-100">
                    Cerrar sesión
                </button>
            </form>

        </div>
    </div>
@endsection