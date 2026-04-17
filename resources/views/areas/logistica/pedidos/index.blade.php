@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content_header')
<h1 class="colorgris body">Pedidos</h1>
@stop

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Distribuidora</th>
                    <th>Puntos</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->distributor->name }}</td>
                        <td>{{ $order->total_points }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('logistica.pedidos.show', $order) }}" class="btn-pro">
                                Ver
                            </a>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection