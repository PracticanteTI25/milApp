@extends('layouts.distribuidores-login')

@section('title', 'Distribuidores - Inicio de sesión')

@section('content')
    <div class="dist-login">
        <div class="dist-container">
            <div class="dist-card">

                <h1 class="dist-title">Inicio de sesión</h1>

                {{-- Mensaje genérico de estado --}}
                @if (session('status'))
                    <div class="dist-alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Errores --}}
                @if ($errors->any())
                    <div class="dist-alert">
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- FORM EMAIL --}}
                <form method="POST" action="{{ route('distribuidores.login.token') }}">
                    @csrf

                    <div class="dist-group">
                        <label class="dist-label">Correo electrónico</label>
                        <input class="dist-input" type="email" name="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus>
                    </div>

                    <div class="dist-actions">
                        <button type="submit" class="dist-btn dist-btn-secondary">
                            Enviar código
                        </button>
                    </div>

                    <div class="dist-forgot">
                        <small>
                            Si el correo está registrado, recibirás un código de acceso.
                        </small>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection