@extends('layouts.admin')

@section('title', 'Panel Administrativo')

@php
    /*
    |--------------------------------------------------------------------------
    | Definición de módulos del panel administrativo
    |--------------------------------------------------------------------------
    | IMPORTANTE:
    | - El panel solo muestra módulos TÉCNICOS / TRANSVERSALES.
    | - Las ÁREAS organizacionales viven exclusivamente en el SIDEBAR.
    | - $enabledModules viene desde routes/web.php.
    */
    $modules = [
        [
            'name' => 'Reportes',
            'slug' => 'reportes',
            'icon' => 'fas fa-chart-bar',
            'route' => route('reportes.index'),
        ],
        [
            'name' => 'Usuarios',
            'slug' => 'usuarios',
            'icon' => 'fas fa-users',
            'route' => route('usuarios.index'),
        ],
    ];

    //Verificar si el usuario tiene AL MENOS un módulo del dashboard
    $dashboardSlugs = collect($modules)->pluck('slug');
    $hasDashboardAccess = $dashboardSlugs->intersect($enabledModules)->isNotEmpty();
@endphp


@section('content_header')
@if ($hasDashboardAccess)
    <h1 class="colorgris body">Panel Administrativo</h1>
@endif
@stop


@section('content')

@if ($hasDashboardAccess)
    <div class="row">

        @foreach ($modules as $module)

            @if (in_array($module['slug'], $enabledModules))
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">

                    <a href="{{ $module['route'] }}" class="text-decoration-none text-reset">
                        <div class="card h-100 shadow-sm module-card">

                            <div class="card-body d-flex align-items-center">
                                <div class="module-icon mr-3">
                                    <i class="{{ $module['icon'] }}"></i>
                                </div>

                                <div>
                                    <h5 class="mb-0">{{ $module['name'] }}</h5>
                                </div>
                            </div>

                        </div>
                    </a>

                </div>
            @endif

        @endforeach

    </div>
@endif

@stop