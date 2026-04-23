@extends('layouts.distribuidores-login')

@section('title', 'Recuperar contraseña')

@section('content')
    <div class="dist-container">
        <div class="dist-card">

            <h1 class="dist-title">Recuperar contraseña</h1>

            <p class="dist-description">
                Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
            </p>

            @if (session('status'))
                <div class="dist-alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('distribuidores.password.email') }}">
                @csrf

                <div class="dist-group">
                    <label class="dist-label">Correo electrónico</label>
                    <input type="email" name="email" class="dist-input" required>
                </div>

                <button type="submit" class="dist-btn dist-btn-primary w-100">
                    Enviar enlace
                </button>
            </form>

        </div>
    </div>
@endsection