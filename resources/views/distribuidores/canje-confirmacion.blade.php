@extends('layouts.app')

@section('title', 'Canje realizado')

@section('content')

    <h1 class="mb-4">✅ Canje realizado con éxito</h1>

    <div class="alert alert-success">
        Tu canje fue procesado correctamente
    </div>

    <div class="card">
        <div class="card-body">

            <p><strong>Número de pedido:</strong> #{{ $order->id }}</p>
            <p><strong>Puntos utilizados:</strong> {{ number_format($order->total_points) }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>

            <hr>

            <h5>Productos canjeados</h5>

            @if($order->items->isEmpty())
                <p>No hay productos en este pedido.</p>
            @else
                <ul>
                    @foreach($order->items as $item)
                        <li>
                            {{ $item->product->name }} —
                            {{ $item->quantity }} unidad(es)
                            ({{ $item->points }} pts)
                        </li>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('distribuidores.catalogo') }}" class="btn btn-primary">
            Volver al catálogo
        </a>
    </div>

@endsection