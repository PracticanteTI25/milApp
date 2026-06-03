@extends('layouts.admin')

@section('title', 'Carga masiva de datos')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <h1>Carga masiva de datos</h1>
        <p class="text-muted">
            Aquí podrás importar distribuidores, direcciones y ventas mensuales mediante archivos Excel.
        </p>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        {{-- ALERTA GENERAL --}}
        <div class="alert alert-warning">
            <strong>Importante:</strong>
            <ul class="mb-0">
                <li>No modificar nombres de columnas en los archivos</li>
                <li>No dejar filas vacías</li>
                <li>No usar fórmulas en Excel</li>
                <li>El identificador obligatorio es el <strong>NIT (document)</strong></li>
            </ul>
        </div>

        {{-- ================= DISTRIBUIDORES ================= --}}
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">1. Carga de distribuidores</h3>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    Este archivo crea o actualiza distribuidores en el sistema.
                </p>

                @if(session('success_distributors'))
                <div class="alert alert-success">
                    {{ session('success_distributors') }}
                </div>
                @endif

                @if(session('error_distributors'))
                <div class="alert alert-danger">
                    {{ session('error_distributors') }}
                </div>
                @endif


                <form method="POST" action="{{ route('comercial.importaciones.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Tipo de importación --}}
                    <input type="hidden" name="type" value="distributors">

                    <div class="form-group">
                        <label>Archivo Excel</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button class="btn btn-primary">
                        Subir distribuidores
                    </button>
                </form>

            </div>
        </div>

        {{-- ================= DIRECCIONES ================= --}}
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">2. Carga de direcciones</h3>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    Permite agregar múltiples direcciones a cada distribuidor.
                </p>

                <div class="alert alert-info">
                    El archivo debe incluir el <strong>NIT</strong> para asociar cada dirección al distribuidor.
                </div>

                @if(session('success_addresses'))
                <div class="alert alert-success">
                    {{ session('success_addresses') }}
                </div>
                @endif

                @if(session('error_addresses'))
                <div class="alert alert-danger">
                    {{ session('error_addresses') }}
                </div>
                @endif

                <form method="POST" action="{{ route('comercial.importaciones.store') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="type" value="addresses">

                    <div class="form-group">
                        <label>Archivo Excel</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button class="btn btn-info">
                        Subir direcciones
                    </button>
                </form>

            </div>
        </div>

        {{-- ================= VENTAS ================= --}}
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">3. Carga de ventas</h3>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    Permite cargar las ventas realizadas por cada distribuidor.
                </p>

                <div class="alert alert-danger">
                    ⚠ Si cargas nuevamente un mismo mes, la información será <strong>reemplazada</strong>.
                </div>

                @if(session('success_sales'))
                <div class="alert alert-success">
                    {{ session('success_sales') }}
                </div>
                @endif

                @if(session('error_sales'))
                <div class="alert alert-danger">
                    {{ session('error_sales') }}
                </div>
                @endif

                <form method="POST" action="{{ route('comercial.importaciones.store') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="type" value="sales">

                    <div class="form-group">
                        <label>Archivo Excel</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button class="btn btn-success">
                        Subir ventas
                    </button>
                </form>

            </div>
        </div>

    </div>
</section>

@endsection