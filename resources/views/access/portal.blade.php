@extends('layouts.access-portal')

@section('title', 'milApp | Acceso')

@section('body-class', 'login-page')

@section('content')

    <div class="portal-container">

        <div class="portal-box">

            {{-- Logo --}}
            <div class="portal-logo">
                <img src="{{ asset('img/portal/logo_gris.png') }}" alt="Milagros">
            </div>

            <p class="portal-subtitle">
                Selecciona tu tipo de acceso
            </p>

            <div class="portal-cards">

                {{-- Acceso corporativo --}}
                <a href="{{ route('login') }}" class="portal-card">

                    <div class="portal-icon-wrapper">
                        <img src="{{ asset('img/portal/logo-V2.png') }}" class="portal-icon-img" alt="Corporativo">
                    </div>

                    <h3>Equipo Corporativo</h3>
                    <p>Uso interno para colaboradores</p>

                </a>

                {{-- Acceso distribuidores --}}
                <a href="{{ route('distribuidores.login') }}" class="portal-card">

                    <div class="portal-icon-wrapper">
                        <img src="{{ asset('img/portal/Carrito.png') }}" class="portal-icon-img portal-icon-cart"
                            alt="Distribuidores">
                    </div>

                    <h3>Distribuidores</h3>
                    <p>Puntos y canjes</p>

                </a>

            </div>

        </div>

    </div>

@endsection