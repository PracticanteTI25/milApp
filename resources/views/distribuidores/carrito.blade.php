@extends('layouts.app')

@section('title', 'Carrito de canje')

@section('content')

    <h1 class="mb-4">Tu carrito</h1>

    @if(empty($cartItems))
        <div class="alert alert-info">
            Tu carrito está vacío.
        </div>

        <a href="{{ route('distribuidores.catalogo') }}" class="btn btn-primary">
            Volver al catálogo
        </a>

    @else

        <div class="cart-grid">

            {{-- LISTA DE PRODUCTOS --}}
            <div class="cart-items">

                @foreach($cartItems as $item)
                    <div class="cart-item">
                        <div>
                            <strong>{{ $item['name'] }}</strong><br>
                            Cantidad: {{ $item['quantity'] }}
                        </div>

                        <div class="cart-item-points">
                            {{ $item['points'] * $item['quantity'] }} pts
                        </div>
                    </div>
                @endforeach

            </div>

            {{-- RESUMEN --}}
            <div class="cart-summary">
                <h3>Resumen</h3>

                <div class="cart-summary-line">
                    <span>Total puntos</span>
                    <strong>{{ $totalPoints }}</strong>
                </div>

                <div class="cart-summary-line">
                    <span>Puntos disponibles</span>
                    <strong>{{ $availablePoints }}</strong>
                </div>

                @if($availablePoints < $totalPoints)
                    <div class="alert alert-danger mt-3">
                        No tienes puntos suficientes para continuar.
                    </div>

                    <button class="btn btn-secondary btn-block" disabled>
                        Continuar canje
                    </button>
                @else
                    <button class="btn btn-primary btn-block" disabled>
                        Continuar canje
                    </button>

                    <small class="text-muted d-block mt-2 text-center">
                        El canje se habilitará en el siguiente paso.
                    </small>
                @endif

            </div>

        </div>

    @endif

@endsection