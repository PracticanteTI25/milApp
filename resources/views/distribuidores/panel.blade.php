@extends('layouts.app')

@section('title', 'Panel de Distribuidores')

@section('content')
    <div class="dist-panel">
        <div class="dist-container">
            <div class="dist-card">

                {{-- HEADER --}}
                <div class="dist-header">
                    <h1 class="dist-title">Panel de Distribuidores</h1>
                    <p class="dist-description">
                        Consulta el catálogo y realiza canjes con tus puntos acumulados.
                    </p>
                </div>

                {{-- ACCIONES --}}
                <div class="dist-actions-panel">
                    <a href="{{ route('distribuidores.catalogo') }}" class="dist-btn dist-btn-primary dist-btn-lg">
                        🛍️ Ver catálogo
                    </a>
                </div>

                {{-- FOOTER --}}
                <div class="dist-footer">
                    <form action="{{ route('distribuidores.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dist-btn dist-btn-secondary w-100">
                            Cerrar sesión
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection