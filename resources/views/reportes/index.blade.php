@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop
@extends('adminlte::page')

@section('title', 'Reportes')

@section('content_header')
<h1 class="body">Dashboard de Reportes</h1>
@stop

@section('content')

<div class="container-fluid">

    <!--  KPIs -->
    <div class="row">

        <div class="col-md-3">
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

        <div class="col-md-3">
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

        <div class="col-md-3">
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

        <div class="col-md-3">
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

    <!--  GRÁFICAS -->
    <div class="row">

        <!-- Gráfica grande -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Rendimiento General</h3>
                </div>
                <div class="card-body">

                    <div style="
                        height:300px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        background:#f4f6f9;
                        border:2px dashed #ccc;
                        border-radius:10px;
                    ">
                        <p>Gráfica de líneas (Power BI)</p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Gráfica lateral -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Distribución</h3>
                </div>
                <div class="card-body">

                    <div style="
                        height:300px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        background:#f4f6f9;
                        border:2px dashed #ccc;
                        border-radius:10px;
                    ">
                        <p>Gráfica de pastel</p>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

@stop