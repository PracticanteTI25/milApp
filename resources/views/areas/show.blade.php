@extends('layouts.admin')

@section('title', 'Área')

@section('content')

<h1 class="colorgris body text-capitalize">
    Área: {{ str_replace('_', ' ', $slug) }}
</h1>

{{-- ================= COMERCIAL ================= --}}
@if($slug === 'comercial')

<div class="mt-3">

    {{-- Gestión de stock --}}
    @if (Route::has('comercial.stock.index'))
    <a href="{{ route('comercial.stock.index') }}"
        class="btn btn-info mr-2 mb-2">
        <i class="fas fa-warehouse"></i>
        Gestión de stock
    </a>
    @endif

    {{-- Metas mensuales --}}
    @if (Route::has('comercial.metas.index'))
    <a href="{{ route('comercial.metas.index') }}"
        class="btn btn-warning mb-2">
        <i class="fas fa-bullseye"></i>
        Metas mensuales
    </a>
    @endif

</div>

{{-- ================= ADMINISTRATIVA Y FINANCIERA ================= --}}
@elseif($slug === 'administrativo_financiero')

<div class="mt-3">


    {{-- Gestión de productos de incentivos --}}
    @if (Route::has('financiera.productos.index'))
    <a href="{{ route('financiera.productos.index') }}"
        class="btn btn-warning mr-2">
        <i class="fas fa-box"></i>
        Productos de incentivos
    </a>
    @else
    <div class="alert alert-info mt-3">
        Este módulo está en construcción. Ya tienes acceso al área
        <b>{{ str_replace('_', ' ', $slug) }}</b>.
    </div>
    @endif


</div>

{{-- ================= LOGÍSTICA ================= --}}
@elseif($slug === 'logistica_distribucion')

<div class="mt-3">

    @if (Route::has('logistica.redenciones.excel'))
    <a href="{{ route('logistica.redenciones.excel') }}"
        class="btn btn-success">
        <i class="fas fa-file-excel"></i>
        Descargar pedidos (Excel)
    </a>
    @else
    <div class="alert alert-info mt-3">
        Este módulo está en construcción. Ya tienes acceso al área
        <b>{{ str_replace('_', ' ', $slug) }}</b>.
    </div>
    @endif

</div>

{{-- ================= CALIDAD ================= --}}
@elseif($slug === 'calidad')
<div class="mt-3">

    @if (Route::has('calidad.devoluciones.index'))
    <a href="{{ route('calidad.devoluciones.index') }}"
        class="btn btn-info mb-2">

        <i class="fas fa-undo-alt"></i>
        Gestión de devoluciones

    </a>
    @endif

</div>

{{-- ================= OTROS MÓDULOS ================= --}}
@else

<div class="card mt-3">
    <div class="card-body">
        <p class="mb-0">
            Este módulo está en construcción. Ya tienes acceso al área
            <b>{{ str_replace('_', ' ', $slug) }}</b>.
        </p>
    </div>
</div>
@endif

@endsection