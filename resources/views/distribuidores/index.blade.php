@extends('layouts.distribuidores')

@section('title', 'Mis puntos')

@section('content')
<div class="w">

    {{-- HERO --}}
    <div class="hero">
        <div class="hero-top">
            <div>
                <div class="hero-brand">Milagros · Mis puntos</div>
                <div class="hero-user">
                    Hola, {{ auth('distributor')->user()->name }}
                </div>
            </div>

            {{-- Meta (temporal fija, luego se conecta a metas reales) --}}
            <div class="badge-goal">
                <span class="g-label">Meta del mes</span>
                <span class="g-val">$15.000.000</span>
                <span class="g-sub">en ventas / mes</span>
            </div>
        </div>

        {{-- RESUMEN --}}
        <div class="pts-row">
            <div class="pts-block">
                <span class="pts-lbl">Disponibles para usar</span>
                <span class="pts-num">{{ $resumen['disponibles'] }}</span>
                <span class="pts-unit">puntos Milagros</span>
            </div>

            <div class="divider-v"></div>

            <div class="pts-block">
                <span class="pts-lbl">Congelados</span>
                <span class="pts-num">{{ $resumen['congelados'] }}</span>
                <span class="pts-unit">puntos</span>
            </div>
        </div>

        {{-- ALERTA DE VENCIMIENTO --}}
        @if($resumen['proximos_a_vencer'] > 0)
        <div class="expire-banner">
            ⚠ {{ $resumen['proximos_a_vencer'] }} puntos se vencerán pronto
        </div>
        @endif
    </div>

    {{-- HISTORIAL --}}
    <button class="hist-btn" onclick="toggleHist()">
        ☰ Ver historial de puntos <span id="hi-arr">►</span>
    </button>

    <div class="panel" id="hist-panel">
        @include('distribuidores.partials.historial', ['historial' => $historial])
    </div>

</div>
@endsection

@push('scripts')
<script>
    function toggleHist() {
        const panel = document.getElementById('hist-panel');
        const arrow = document.getElementById('hi-arr');
        panel.classList.toggle('open');
        arrow.innerText = panel.classList.contains('open') ? '▼' : '►';
    }
</script>
@endpush