@extends('layouts.distribuidores-login')

@section('title', 'Distribuidores - Código de acceso')

@section('content')
    <div class="dist-login">
        <div class="dist-container">
            <div class="dist-card">

                <h1 class="dist-title">Código de acceso</h1>

                {{-- Mensaje de estado --}}
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

                {{-- FORMULARIO 1: VALIDAR TOKEN --}}
                <form method="POST" action="{{ route('distribuidores.login.token.verify') }}">
                    @csrf

                    <div class="dist-group">
                        <label class="dist-label">Código de 6 dígitos</label>
                        <input class="dist-input" type="text" name="token" inputmode="numeric" pattern="[0-9]{6}"
                            maxlength="6" required autofocus>
                    </div>

                    <div class="dist-actions">
                        <button type="submit" class="dist-btn dist-btn-secondary">
                            Ingresar
                        </button>
                    </div>
                </form>

                {{-- FORMULARIO 2: REENVIAR CÓDIGO --}}
                <div class="dist-resend" data-remaining="{{ $resendRemaining ?? 0 }}">

                    <p id="resend-timer" class="dist-resend-disabled">
                        Puedes reenviar el código en
                        <strong><span id="resend-seconds">{{ $resendRemaining ?? 0 }}</span></strong>
                        segundos
                    </p>

                    <form method="POST" action="{{ route('distribuidores.login.token.resend') }}">
                        @csrf
                        <button type="submit" id="resend-button" class="dist-resend-btn" style="display:none;">
                            ¿No recibiste el código? <span>Reenviar</span>
                        </button>
                    </form>

                </div>

                <div class="dist-forgot">
                    <small>
                        El código expira en 5 minutos.
                    </small>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const container = document.querySelector('.dist-resend');
            if (!container) return;

            let remaining = parseInt(container.dataset.remaining, 10);

            const timerText = document.getElementById('resend-timer');
            const secondsSpan = document.getElementById('resend-seconds');
            const resendButton = document.getElementById('resend-button');

            if (isNaN(remaining) || remaining <= 0) {
                timerText.style.display = 'none';
                resendButton.style.display = 'inline-block';
                return;
            }

            const interval = setInterval(() => {
                remaining--;
                secondsSpan.textContent = remaining;

                if (remaining <= 0) {
                    clearInterval(interval);
                    timerText.style.display = 'none';
                    resendButton.style.display = 'inline-block';
                }
            }, 1000);
        });
    </script>
@endsection