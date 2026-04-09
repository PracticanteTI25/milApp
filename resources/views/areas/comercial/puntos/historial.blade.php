@extends('layouts.admin')

@section('title', 'Historial de puntos')

@section('content_header')
<h1 class="colorgris body">
    Historial de puntos: {{ $distributor->name }}
</h1>
@stop

@section('content')


    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Movimiento</th>
                        <th>Tipo</th>
                        <th>Comentario</th>
                        <th>Saldo después</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>

                            <td>
                                @if($m->delta > 0)
                                    <span class="text-success">+{{ $m->delta }}</span>
                                @else
                                    <span class="text-danger">{{ $m->delta }}</span>
                                @endif
                            </td>

                            <td>
                                {{ ucfirst(str_replace('_', ' ', $m->type)) }}
                            </td>

                            <td>
                                {{ $m->comment ?? '-' }}
                            </td>

                            <td>
                                <strong>{{ $m->balance_after }}</strong>
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

            {{ $movements->links() }}

        </div>
    </div>

@endsection