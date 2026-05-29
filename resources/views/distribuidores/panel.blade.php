@extends('layouts.app')

@section('title', 'Panel de Distribuidores')

@section('content')
<div class="dist-panel">
    <div class="dist-container">

        <div class="dist-card">

            {{-- HEADER --}}
            <div class="dist-header text-center">
                <h6 class="dist-title text-uppercase" style="opacity: 0.6;">
                    ¿QUÉ DESEAS HACER?
                </h6>
            </div>

            {{-- OPCIONES --}}
            <div class="dist-actions-panel">

                {{-- OPCIÓN CATÁLOGO --}}
                <a href="{{ route('distribuidores.catalogo') }}" class="dist-action-card">

                    <div class="dist-action-icon">
                        📦
                    </div>

                    <div class="dist-action-content">
                        <strong>Ver catálogo y canjear</strong>
                        <small>
                            Explora premios y usa tus puntos acumulados
                        </small>
                    </div>

                    <div class="dist-action-arrow">
                        →
                    </div>

                </a>

                {{-- OPCIÓN DEVOLUCIONES --}}
                <a href="{{ route('distribuidores.devoluciones.create') }}" class="dist-action-card">

                    <div class="dist-action-icon dist-action-green">
                        🔄
                    </div>

                    <div class="dist-action-content">
                        <strong>Devolución de producto</strong>
                        <small>
                            Registra un producto y haz seguimiento
                        </small>
                    </div>

                    <div class="dist-action-arrow">
                        →
                    </div>

                </a>

            </div>

            {{-- FOOTER --}}
            <div class="dist-footer">
                <form action="{{ route('distribuidores.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dist-btn dist-btn-secondary dist-btn-footer">
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection