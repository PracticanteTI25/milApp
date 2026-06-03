@extends('layouts.app')

@section('title', 'Mis puntos')

@section('css')

<link rel="stylesheet" href="{{ asset('css/distribuidores.css') }}">
<link rel="stylesheet" href="{{ asset('css/points.css') }}">
@endsection

@include('distribuidores.partials.navbar')

@section('content')

<div class="w">

    <div class="hero">
        <div class="hero-top">
            <div>
                <div class="hero-brand">Milagros · Mis puntos</div>
                <div class="hero-user">
                    Hola, {{ auth('distributor')->user()->name ?? '' }}
                </div>
            </div>

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

        @if($resumen['proximos_a_vencer'] > 0)
        <div class="expire-banner">
            ⚠ {{ $resumen['proximos_a_vencer'] }} puntos se vencerán pronto
        </div>
        @endif
    </div>

    <div class="metrics">

        <div class="mc">
            <div class="mc-lbl">Resultado del mes</div>

            <div class="mc-val">
                {{ number_format($percentage, 0) }}%
            </div>

            <div class="mc-sub">
                ${{ number_format($achieved, 0, ',', '.') }} de
                ${{ number_format($goal->goal_amount ?? 0, 0, ',', '.') }}
            </div>

            @if($percentage >= 100)
            <span class="badge bg-success">Meta cumplida</span>
            @else
            <span class="badge bg-danger">Pendiente</span>
            @endif
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

    {{-- SECCIÓN MODIFICADA --}}
    <div class="progress-wrap">
        <div class="prog-title">Resultado del mes</div>

        @if(!$monthlyGoal)
        <div class="alert alert-warning">
            No hay meta definida para este mes.
        </div>
        @elseif(!$monthlyGoal->sale)
        <div class="alert alert-info">
            Ventas pendientes de carga para este mes.
        </div>
        @else

        @php
        $goalAmount = $monthlyGoal->goal_amount ?? 0;
        $achieved = $monthlyGoal->sale->achieved_amount ?? 0;

        $percentage = $goalAmount > 0
        ? ($achieved / $goalAmount) * 100
        : 0;

        $percentage = min($percentage, 100);
        @endphp

        <div class="progress mb-2" style="height: 12px;">
            <div
                class="progress-bar 
                    {{ $percentage >= 100 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}"
                role="progressbar"
                style="width: {{ $percentage }}%;">
            </div>
        </div>

        <div class="d-flex justify-content-between small text-muted">
            <span>
                ${{ number_format($achieved, 0, ',', '.') }} ventas
            </span>
            <span>
                {{ number_format($percentage, 2) }}% · meta
                ${{ number_format($goalAmount, 0, ',', '.') }}
            </span>
        </div>

        @endif
    </div>
    {{-- FIN MODIFICACIÓN --}}

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