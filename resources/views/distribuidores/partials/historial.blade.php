<div class="panel-head">Historial completo de puntos</div>

<div style="overflow-x:auto;">
    <table class="tbl">
        <thead>
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
                <td><strong>{{ $item['puntos'] }}</strong></td>
                <td>
                    <strong>{{ $item['estado'] }}</strong>
                    <div class="note">
                        {{ $item['disponibles'] }} disponibles · {{ $item['congelados'] }} congelados
                    </div>
                </td>
                <td>
                    @if(!empty($item['vencimientos']))
                    {{ implode(', ', $item['vencimientos']) }}
                    @else
                    —
                    @endif
                </td>
                <td>
                    @foreach($item['detalle'] as $mov)

                    @php
                    $impactoTexto = match($mov->impacto) {
                    'suma_habilitada' => 'Puntos habilitados',
                    'suma_congelada' => 'Puntos congelados',
                    'resta' => 'Puntos descontados',
                    default => 'Movimiento de puntos',
                    };
                    @endphp

                    <div class="note">
                        <!-- <strong>[{{ $impactoTexto }}]</strong> -->

                        @if($mov->puntos < 0)
                            <span style="color:#dc3545;font-weight:600;">
                            −{{ abs($mov->puntos) }} pts
                            </span>
                            @else
                            <span style="font-weight:600;">
                                +{{ $mov->puntos }} pts
                            </span>
                            @endif

                            · {{ $mov->descripcion }}
                    </div>

                    @endforeach
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No hay movimientos aún.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>