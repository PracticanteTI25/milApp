@extends('adminlte::page')

@section('title', 'Reportes')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content_header')
<h1 class="body text-center text-md-left">Dashboard de Reportes</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="small-box bg-mi-color">
                <div class="inner">
                    <h3>150</h3>
                    <p>Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="small-box bg-mi-color">
                <div class="inner">
                    <h3>320</h3>
                    <p>Citas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="small-box bg-mi-color">
                <div class="inner">
                    <h3>$12K</h3>
                    <p>Ingresos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="small-box bg-mi-color">
                <div class="inner">
                    <h3>85%</h3>
                    <p>Satisfacción</p>
                </div>
                <div class="icon">
                    <i class="fas fa-smile"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-12 col-lg-8 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Rendimiento General</h3>
                </div>
                <div class="card-body">

                    <div class="grafica-box">
                        <p>Gráfica de líneas (Power BI)</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Distribución</h3>
                </div>
                <div class="card-body">

                    <div class="grafica-box">
                        <p>Gráfica de pastel</p>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

@stop