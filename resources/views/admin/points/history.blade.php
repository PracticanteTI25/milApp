@extends('layouts.admin')

@section('title', 'Historial de puntos')

@section('content')

<h1 class="mb-4">Historial de puntos</h1>

<p class="text-muted">
    Consulta completa de puntos de una distribuidora.
    Esta vista muestra la información tal como se usa para soporte y reclamos.
</p>

<form method="GET" action="{{ route('admin.puntos.historial') }}" class="mb-4">
    <div class="row align-items-end">
        <div class="col-md-6">
            <label class="form-label">Distribuidora</label>
            <select name="distributor_id" class="form-control" required>
                <option value="">Seleccione una distribuidora</option>
                @foreach($distributors as $d)
                <option value="{{ $d->id }}"
                    {{ optional($selectedDistributor)->id === $d->id ? 'selected' : '' }}>
                    {{ $d->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Ver historial</button>
        </div>
    </div>
</form>

@if($selectedDistributor)

{{-- ================= RESUMEN ================= --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>{{ $selectedDistributor->name }}</strong>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="h4">{{ $resumen['disponibles'] }}</div>
                <small class="text-muted">Disponibles</small>
            </div>
            <div class="col-md-3">
                <div class="h4">{{ $resumen['congelados'] }}</div>
                <small class="text-muted">Congelados</small>
            </div>
            <div class="col-md-3">
                <div class="h4">{{ $resumen['proximos_a_vencer'] }}</div>
                <small class="text-muted">Próximos a vencer</small>
            </div>
            <div class="col-md-3">
                <div class="h4">
                    ${{ number_format($metaMensual, 0, ',', '.') }}
                </div>
                <small class="text-muted">
                    Meta {{ $currentMonth }}/{{ $currentYear }}
                </small>
            </div>
        </div>
    </div>
</div>

{{-- ================= CONGELADOS ================= --}}
@if(!empty($congeladosDetalle))
<div class="card mb-4">
    <div class="card-header">Detalle de puntos congelados</div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="thead-light">
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
                    <td>{{ $item['puntos'] }}</td>
                    <td>{{ $item['vencen'] ?? '—' }}</td>
                    <td>{{ $item['motivo'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ================= HISTORIAL ================= --}}
<div class="card">
    <div class="card-header">Historial completo de puntos</div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Mes</th>
                    <th>Puntos</th>
                    <th>Estado</th>
                    <th>Vencen</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historial as $item)
                <tr>
                    <td>{{ $item['mes'] }}</td>
                    <td>{{ $item['puntos'] }}</td>
                    <td>{{ ucfirst($item['estado']) }}</td>
                    <td>
                        {{ $item['fecha_vencimiento']
                            ? \Carbon\Carbon::parse($item['fecha_vencimiento'])->format('d/m/Y')
                            : '—'
                        }}
                    </td>
                    <td>
                        @foreach($item['detalle'] as $mov)
                        <div class="small text-muted">
                            {{ $mov->descripcion }}
                        </div>
                        @endforeach
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No hay movimientos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endif

@endsection