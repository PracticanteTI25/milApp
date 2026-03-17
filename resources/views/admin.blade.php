@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop
@extends('adminlte::page')

@section('title', 'Panel')

@section('content_header')
<h1 class="colorgris">Panel Administrativo</h1>
@stop

@section('content')

<div class="row">

    {{-- Tarjeta ejemplo --}}
    <div class="col-md-3">
        <div class="small-box bg-mi-color">
            <div class="inner">
                <h3>Usuarios</h3>
                <a href="{{ route('usuarios.index') }}" class="btn btn-light btn-sm mt-2 btn-rosaClaro">
                    Gestionar
                </a>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

</div>

@stop