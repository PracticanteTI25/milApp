@extends('layouts.devoluciones')

@section('title', 'Devoluciones')

@section('content')
<div class="devoluciones-wrapper">

    <div class="devoluciones-card">

        <h2 class="devoluciones-title">Formulario de Devolución</h2>

        <form action="{{ route('devoluciones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- REFERENCIA LOTE --}}
            <div class="devoluciones-group">
                <label>Referencia Lote</label>
                <select name="lote" class="devoluciones-select">
                    <option value="">Busca o selecciona un producto...</option>
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

                <label class="devoluciones-upload">
                    <input type="file" name="imagen" hidden>

                    <p><strong>Haz clic o arrastra</strong></p>
                    <small>JPG, PNG, WEBP – máx. 10MB</small>
                </label>
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