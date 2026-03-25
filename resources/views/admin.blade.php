@extends('adminlte::page')

@section('title', 'Panel')

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
@stop

@section('content_header')
<h1 class="colorgris body">Panel Administrativo</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4">

            <div class="small-box bg-mi-color">

                <div class="inner">
                    <h3 class="mb-2">Usuarios</h3>
                </div>

                <div class="px-3 pb-3">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-light btn-sm btn-rosaClaro">
                        Gestionar
                    </a>
                </div>

                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>

            </div>

        </div>

    </div>

</div>

@stop