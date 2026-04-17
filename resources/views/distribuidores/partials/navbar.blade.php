@php
    $nav = config('distribuidores_nav');
    $cartCount = count(session('cart', []));
@endphp

<nav class="dist-navbar">
    <div class="dist-navbar-inner">

        {{-- Marca --}}
        <div class="dist-navbar-brand">
            {{ $nav['brand'] }}
        </div>

        {{-- Items --}}
        <div class="dist-navbar-links">

            <!-- CATÁLOGO -->
            <a href="{{ route('distribuidores.catalogo') }}" class="dist-link">
                Catálogo
            </a>

            <!-- CARRITO -->
            <a href="{{ route('distribuidores.carrito.index') }}" class="dist-link">
                Carrito
                @if($cartCount > 0)
                    <span class="dist-badge">{{ $cartCount }}</span>
                @endif
            </a>

            <div class="dist-navbar-separator"></div>

            <!-- LOGOUT -->
            <form action="{{ route('distribuidores.logout') }}" method="POST"
                style="display: inline-flex; align-items: center;">
                @csrf
                <button type="submit" class="dist-logout-btn">
                    Cerrar sesión
                </button>
            </form>

        </div>

    </div>
</nav>