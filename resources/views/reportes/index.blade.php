@extends('layouts.admin')

@section('title', 'Reportes')

@section('content_header')
<h1 class="colorgris body">Portal de Reportes</h1>
@stop

@section('content')

<div class="row">

    @foreach ($dashboards as $id => $dashboard)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">

            <a href="{{ route('reportes.show', $id) }}" class="text-decoration-none text-dark">

                <div class="card h-100 shadow-sm dashboard-card">

                    <div class="card-body d-flex flex-column justify-content-between">

                        <div class="text-center mb-3">
                            <i class="{{ $dashboard['icon'] }} fa-3x text-primary"></i>
                        </div>

                        <div class="text-center">
                            <h5 class="card-title mb-1">
                                {{ $dashboard['title'] }}
                            </h5>

                            <p class="card-text text-muted small">
                                {{ $dashboard['description'] }}
                            </p>
                        </div>

                        <div class="text-center mt-3">
                            <span class="badge badge-light">
                                Ver tablero
                            </span>
                        </div>

                    </div>

                </div>

            </a>

        </div>
    @endforeach

</div>

@stop