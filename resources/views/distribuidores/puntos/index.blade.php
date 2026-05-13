@extends('layouts.app')

@section('title', 'Mis puntos')

{{-- CSS necesario SOLO para esta vista --}}
@section('css')
<link rel="stylesheet" href="{{ asset('css/distribuidores.css') }}">
<link rel="stylesheet" href="{{ asset('css/points.css') }}">
@endsection

{{-- Navbar igual que catálogo --}}
@include('distribuidores.partials.navbar')

@section('content')
<div class="w">

    {{-- HERO --}}
    <div class="hero">
        <div class="hero-top">
            <div>
                <div class="hero-brand">Milagros · Mis puntos</div>
                <div class="hero-user">
                    Hola, {{ auth('distributor')->user()->name ?? '' }}
                </div>
            </div>

            {{-- Meta --}}
            <div class="badge-goal">
                <span class="g-label">
                    Meta del mes ({{ $currentMonth }}/{{ $currentYear }})
                </span>

                @if($monthlyGoal)
                <span class="g-val">
                    ${{ number_format($monthlyGoal->goal_amount, 0, ',', '.') }}
                </span>
                <span class="g-sub">en ventas / mes</span>
                @else
                <span class="g-val text-muted">
                    Sin meta asignada
                </span>
                <span class="g-sub">
                    Contacta a tu asesor comercial
                </span>
                @endif
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

                @if($resumen['congelados'] > 0)
                <button class="frozen-pill" id="toggle-frozen">
                    <span class="dot"></span>
                    {{ $resumen['congelados'] }} puntos ▼
                </button>
                <small style="opacity:.7">toca para ver detalle</small>
                @else
                <span class="pts-num">0</span>
                <span class="pts-unit">puntos</span>
                @endif
            </div>
        </div>

        {{-- ALERTA DE VENCIMIENTO --}}
        @if($resumen['proximos_a_vencer'] > 0)
        <div class="expire-banner">
            ⚠ {{ $resumen['proximos_a_vencer'] }} puntos se vencerán pronto
        </div>
        @endif
    </div>

    <div class="metrics">

        <div class="mc">
            <div class="mc-lbl">Falta para tu meta</div>
            <div class="mc-val">
                ${{ number_format($faltanteMeta, 0, ',', '.') }}
            </div>
            <div class="mc-sub">en ventas este mes</div>
        </div>

        <div class="mc">
            <div class="mc-lbl">Valor en crédito</div>
            <div class="mc-val">
                {{ number_format($valorCredito, 0, ',', '.') }}
            </div>
            <div class="mc-sub">puntos disponibles</div>
        </div>

        <div class="mc">
            <div class="mc-lbl">Canjes este año</div>
            <div class="mc-val">{{ $canjesAnio }}</div>
            <div class="mc-sub">redenciones realizadas</div>
        </div>

    </div>

    <div class="progress-wrap">
        <div class="prog-title">Avance hacia la meta del mes</div>

        <div class="prog-track">
            <div class="prog-fill" style="width: {{ $porcentajeMeta }}%;"></div>
        </div>

        <div class="prog-labels">
            <span>
                ${{ number_format($ventasAcumuladas, 0, ',', '.') }} acumulados
            </span>
            <span>
                {{ $porcentajeMeta }}% · meta ${{ number_format($metaMensual, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- DETALLE DE CONGELADOS --}}
    @if(!empty($congeladosDetalle))
    <div class="panel" id="frozen-panel">
        <div class="panel-head">Detalle de puntos congelados</div>

        <table class="tbl">
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Puntos</th>
                    <th>Vencen</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($congeladosDetalle as $item)
                <tr>
                    <td>{{ $item['mes'] }}</td>
                    <td><strong>{{ $item['puntos'] }}</strong></td>
                    <td>{{ $item['vencen'] ?? '—' }}</td>
                    <td class="note">{{ $item['motivo'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- HISTORIAL --}}
    <button class="hist-btn" id="toggle-hist">
        ☰ Ver historial de puntos <span id="hi-arr">►</span>
    </button>

    <div class="panel" id="hist-panel">
        @include('distribuidores.partials.historial', ['historial' => $historial])
    </div>

</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const histBtn = document.getElementById('toggle-hist');
        const histPanel = document.getElementById('hist-panel');
        const histArrow = document.getElementById('hi-arr');

        if (histBtn && histPanel && histArrow) {
            histBtn.addEventListener('click', function() {
                histPanel.classList.toggle('open');
                histArrow.textContent = histPanel.classList.contains('open') ? '▼' : '►';
            });
        }

        const frozenBtn = document.getElementById('toggle-frozen');
        const frozenPanel = document.getElementById('frozen-panel');

        if (frozenBtn && frozenPanel) {
            frozenBtn.addEventListener('click', function() {
                frozenPanel.classList.toggle('open');
            });
        }

    });
</script>
@endsection