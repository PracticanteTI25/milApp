@extends('layouts.admin')

@section('title', 'Productos')

@section('content_header')
<h1 class="colorgris body">Productos</h1>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="table-responsive mt-3">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Puntos</th>
                <th>Stock</th>
                <th>Activo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
            <tr>
                <td>
                    {{ $p->name }}
                    @if($p->presentation)
                    <div class="text-muted small">{{ $p->presentation }}</div>
                    @endif
                </td>

                <td>{{ optional($p->currentPrice)->points ?? '-' }}</td>

                <td>{{ $p->stock }}</td>

                <td>{{ $p->active ? 'Sí' : 'No' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted">
                    No hay productos registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection