@extends('layouts.admin')

@section('title', 'Detalle del pedido')

@section('content_header')
<h1 class="colorgris body">
    Pedido #{{ $order->id }}
</h1>
@stop

@section('content')

    {{-- ACCIONES --}}
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('logistica.pedidos.pdf', $order) }}" class="btn btn-danger btn-sm" target="_blank">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
    </div>

    {{-- INFO PEDIDO --}}
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Distribuidora:</strong> {{ $order->distributor->name }}</p>
            <p><strong>Puntos usados:</strong> {{ $order->total_points }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Fecha:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    {{-- PRODUCTOS --}}
    <div class="card">
        <div class="card-header">
            Productos
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->points }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('logistica.pedidos.index') }}" class="btn btn-secondary btn-sm">
            ← Volver
        </a>
    </div>

@endsection