@extends('layouts.admin')

@section('title', 'Control de puntos')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold">Control de puntos</h1>
    <p class="text-muted">
        Administra reglas, vencimientos y ajustes del sistema de puntos.
    </p>
</div>

<div class="row g-4">

    {{-- CONFIGURACIÓN --}}
    <div class="col-md-4">
        <div class="card card-hover h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column">

                <div class="mb-3 fs-1 text-primary">
                    <i class="fas fa-cogs"></i>
                </div>

                <h5 class="card-title fw-semibold">
                    Configuración de puntos
                </h5>

                <p class="card-text text-muted flex-grow-1">
                    Define reglas globales como vencimiento y comportamiento de los puntos.
                </p>

                <a href="{{ route('admin.puntos.configuracion') }}"
                    class="btn btn-primary mt-auto">
                    Ir a configuración
                </a>

            </div>
        </div>
    </div>

    {{-- AJUSTES --}}
    <div class="col-md-4">
        <div class="card card-hover h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column">

                <div class="mb-3 fs-1 text-warning">
                    <i class="fas fa-sliders-h"></i>
                </div>

                <h5 class="card-title fw-semibold">
                    Ajustes manuales
                </h5>

                <p class="card-text text-muted flex-grow-1">
                    Suma o resta puntos manualmente con trazabilidad y auditoría.
                </p>

                <a href="{{ route('admin.puntos.ajustes') }}"
                    class="btn btn-warning text-white mt-auto">
                    Gestionar ajustes
                </a>

            </div>
        </div>
    </div>

    {{-- HISTORIAL --}}
    <div class="col-md-4">
        <div class="card card-hover h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column">

                <div class="mb-3 fs-1 text-secondary">
                    <i class="fas fa-history"></i>
                </div>

                <h5 class="card-title fw-semibold">
                    Historial de puntos
                </h5>

                <p class="card-text text-muted flex-grow-1">
                    Consulta el historial completo de puntos de cualquier distribuidora.
                </p>

                <a href="{{ route('admin.puntos.historial') }}"
                    class="btn btn-secondary mt-auto">
                    Ver historial
                </a>

            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .card-hover {
        transition: all 0.25s ease;
        border-radius: 16px;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .card-title {
        font-size: 1.2rem;
    }
</style>
@endpush