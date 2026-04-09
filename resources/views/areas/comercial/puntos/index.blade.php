@extends('layouts.admin')

@section('title', 'Asignación de puntos')

@section('content_header')
<h1 class="colorgris body">Asignación de puntos</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Distribuidora</th>
                    <th>Correo</th>
                    <th>Puntos actuales</th>
                    <th>Puntos redimidos</th>
                    <th>+ / -</th>
                    <th>Cantidad</th>
                    <th>Comentario</th>
                    <th>Acción</th>
                    <th>Historial</th>
                </tr>
            </thead>

            <tbody>
                @foreach($distributors as $d)
                    <tr class="{{ $d->active ? '' : 'text-muted' }}">
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->email }}</td>
                        <td><strong>{{ $d->points_balance }}</strong></td>
                        <td>{{ $d->points_redeemed }}</td>

                        {{-- Form por fila, pero cada input en su columna --}}
                        <td>
                            <form action="{{ route('comercial.puntos.update', $d->id) }}" method="POST" class="form-inline">
                                @csrf

                                <select name="operation" class="form-control form-control-sm" required>
                                    <option value="plus">+</option>
                                    <option value="minus">-</option>
                                </select>
                        </td>

                        <td>
                            <input name="amount" type="number" min="1" class="form-control form-control-sm" placeholder="Ej: 50"
                                required style="width:90px;">
                        </td>

                        <td>
                            <input name="comment" type="text" class="form-control form-control-sm"
                                placeholder="Comentario (opcional)" style="min-width:180px;">
                        </td>

                        <td>
                            <button class="btn btn-primary btn-sm">Actualizar</button>
                            </form>
                        </td>
                        
                        <td>
                            <a href="{{ route('comercial.puntos.historial', $d->id) }}"
                                class="btn btn-outline-secondary btn-sm">
                                Ver historial
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection