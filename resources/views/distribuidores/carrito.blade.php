@extends('layouts.app')

@include('distribuidores.partials.navbar')

@section('title', 'Carrito de canje')

@section('content')

    <h1 class="mb-4">Tu carrito</h1>

    @if(empty($cartItems))
        <div class="alert alert-info">
            Tu carrito está vacío
        </div>

    @else

        <div class="cart-grid">

            {{-- LISTA DE PRODUCTOS --}}
            <div class="cart-items">

                @foreach($cartItems as $item)
                    <div class="cart-item">

                        {{-- BOTÓN ELIMINAR --}}
                        <div class="cart-item-remove">
                            <form method="POST" action="{{ route('distribuidores.carrito.remove') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">

                                <button type="submit" class="cart-remove-btn"
                                    onclick="return confirm('¿Eliminar este producto del carrito?')">
                                    ✕
                                </button>
                            </form>
                        </div>

                        {{-- Imagen --}}
                        <div class="cart-item-image">
                            @if(!empty($item['image']))
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="Sin imagen">
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="cart-item-info">
                            <strong>{{ $item['name'] }}</strong>

                            {{-- FORM ACTUALIZAR --}}
                            <form method="POST" action="{{ route('distribuidores.carrito.update') }}">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">

                                <label>Cantidad</label>
                                <input type="number" name="quantity" min="1" value="{{ $item['quantity'] }}" class="cart-qty"
                                    onchange="this.form.submit()">
                            </form>
                        </div>

                        {{-- Puntos --}}
                        <div class="cart-item-points">
                            {{ number_format($item['points'] * $item['quantity']) }} pts
                        </div>

                    </div>
                @endforeach


            </div>

            {{-- RESUMEN --}}
            <div class="cart-summary">
                <h3>Resumen</h3>

                <div class="cart-summary-line">
                    <span>Total puntos</span>
                    <strong>{{ number_format($totalPoints) }}</strong>
                </div>

                <div class="cart-summary-line">
                    <span>Puntos disponibles</span>
                    <strong>{{ $availablePoints }}</strong>
                </div>

                @if($availablePoints < $totalPoints)
                    <div class="alert alert-danger mt-3">
                        No tienes puntos suficientes para continuar
                    </div>

                    <button class="btn btn-secondary btn-block" disabled>
                        Continuar canje
                    </button>
                @else
                    <form method="POST" action="{{ route('distribuidores.canje.store') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block">
                            Continuar canje
                        </button>
                    </form>
                @endif

            </div>

        </div>

    @endif

@endsection