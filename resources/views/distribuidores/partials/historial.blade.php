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
                    <span class="tag t-{{ $item['estado'] }}">
                        {{ ucfirst($item['estado']) }}
                    </span>
                </td>
                <td>
                    {{ $item['fecha_vencimiento']
                            ? \Carbon\Carbon::parse($item['fecha_vencimiento'])->format('d/m/Y')
                            : '—'
                        }}
                </td>
                <td>
                    @foreach($item['detalle'] as $mov)
                    <div class="note">{{ $mov->descripcion }}</div>
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