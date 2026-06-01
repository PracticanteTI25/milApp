@extends('layouts.devoluciones')

@section('title', 'Devoluciones')

@section('content')

<div class="devoluciones-wrapper">

    <div class="devoluciones-card">

        <h2 class="devoluciones-title">Formulario de Devolución</h2>

        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('devoluciones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- REFERENCIA LOTE --}}
            <div class="devoluciones-group">
                <label>Referencia Lote</label>
                <select name="lote" class="devoluciones-select">
                    <option value="">Busca o selecciona un producto...</option>
                    @foreach($lotes as $lote)
                    <option value="{{ $lote['codigo'] }}">
                        {{ $lote['nombre'] }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- CANTIDAD --}}
            <div class="devoluciones-group">
                <label>Cantidad a devolver</label>
                <input type="number" name="cantidad" class="devoluciones-input" placeholder="Ej: 3">
            </div>

            {{-- IMAGEN --}}
            <div class="devoluciones-group">
                <label>Adjunta imagen</label>

                <input
                    type="file"
                    name="imagen"
                    class="devoluciones-input-file"
                    accept="image/*">

                <small class="file-help">
                    JPG, PNG, WEBP – máx. 10MB
                </small>
            </div>

            {{-- OBSERVACIONES --}}
            <div class="devoluciones-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="devoluciones-textarea"
                    placeholder="Describe detalladamente el motivo de la devolución..."></textarea>
            </div>

            {{-- BOTÓN --}}
            <button class="devoluciones-btn">
                Enviar solicitud
            </button>

        </form>

    </div>

</div>
@endsection

