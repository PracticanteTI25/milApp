@extends('layouts.distribuidores-login')

@section('title', 'Restablecer contraseña')

@section('content')
    <div class="dist-container">
        <div class="dist-card">

            <h1 class="dist-title">Nueva contraseña</h1>

            <form method="POST" action="{{ route('distribuidores.password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="dist-group">
                    <label class="dist-label">Nueva contraseña</label>
                    <input type="password" name="password" class="dist-input" required>
                </div>

                <div class="dist-group">
                    <label class="dist-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="dist-input" required>
                </div>

                <button type="submit" class="dist-btn dist-btn-primary w-100">
                    Guardar contraseña
                </button>
            </form>

        </div>
    </div>
@endsection