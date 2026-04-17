@extends('layouts.app')

@section('title', 'Distribuidores - Inicio de sesión')

@section('content')
    <div class="dist-container">
        <div class="dist-card">

            <h1 class="dist-title">Inicio de Sesión</h1>

            {{-- Mensaje de error general --}}
            @if ($errors->any())
                <div class="dist-alert">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- FORM CORREGIDO --}}
            <form method="POST" action="{{ route('distribuidores.login.process') }}">
                @csrf

                <div class="dist-group">
                    <label class="dist-label">Correo electrónico</label>
                    <input class="dist-input" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email">
                </div>

                <div class="dist-group">
                    <label class="dist-label">Contraseña</label>
                    <input class="dist-input" type="password" name="password" required autocomplete="current-password">
                </div>

                <div class="dist-actions">
                    <button type="submit" class="dist-btn dist-btn-secondary">
                        Acceder
                    </button>

                    <a href="#" class="dist-btn dist-btn-secondary">
                        Crear una cuenta
                    </a>
                </div>

                <div class="dist-forgot">
                    <a href="#">¿Has olvidado tu contraseña?</a>
                </div>
            </form>

        </div>
    </div>
@endsection