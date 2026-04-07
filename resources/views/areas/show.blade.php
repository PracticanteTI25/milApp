@extends('layouts.admin')

@section('title', 'Área')

@section('content')
    <h1 class="colorgris body text-capitalize">Área: {{ str_replace('_', ' ', $slug) }}</h1>

    @if($slug === 'comercial')
        <div class="mt-3">
            <a href="{{ route('comercial.distribuidores.index') }}" class="btn btn-primary">
                Registro de distribuidoras
            </a>
        </div>
    @else
        <div class="card mt-3">
            <div class="card-body">
                <p class="mb-0">
                    Este módulo está en construcción. Ya tienes acceso al área <b>{{ $slug }}</b>.
                </p>
            </div>
        </div>
    @endif

@endsection