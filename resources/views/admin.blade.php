@extends('layouts.admin')

@section('title', 'Panel Administrativo')

@section('content_header')
<h1 class="colorgris body">Panel Administrativo</h1>
@stop

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Definición de módulos del panel administrativo
    |--------------------------------------------------------------------------
    | IMPORTANTE:
    | - El panel solo muestra módulos TÉCNICOS / TRANSVERSALES.
    | - Las ÁREAS organizacionales viven exclusivamente en el SIDEBAR.
    | - El acceso real se controla por permisos en backend.
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
@endphp


<div class="row">

    @foreach ($modules as $module)

        @php
            $isEnabled = in_array($module['slug'], $enabledModules);
        @endphp

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">

            @if ($isEnabled)
                {{-- MÓDULO HABILITADO --}}
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
            @else
                {{-- MÓDULO DESHABILITADO --}}
                <div class="card h-100 shadow-sm module-card-disabled">
                    <div class="card-body d-flex align-items-center text-muted">

                        <div class="module-icon mr-3">
                            <i class="{{ $module['icon'] }}"></i>
                        </div>

                        <div>
                            <h5 class="mb-1">{{ $module['name'] }}</h5>
                            <small>
                                <i class="fas fa-lock"></i> Acceso restringido
                            </small>
                        </div>

                    </div>
                </div>
            @endif

        </div>

    @endforeach

</div>

@stop