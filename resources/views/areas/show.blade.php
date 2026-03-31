@extends('layouts.admin')

@section('title', 'Área')

@section('content')
    <h1 class="colorgris body text-capitalize">Área: {{ str_replace('_', ' ', $slug) }}</h1>

    <div class="card mt-3">
        <div class="card-body">
            <p class="mb-0">
                Este módulo está en construcción. Ya tienes acceso al área <b>{{ $slug }}</b>.
            </p>
        </div>
    </div>
@endsection