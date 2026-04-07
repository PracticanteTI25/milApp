<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Distribuidores - Inicio de sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS exclusivo distribuidores -->
    <link rel="stylesheet" href="{{ asset('css/distribuidores-login.css') }}?v=2">
</head>

<body class="dist-body">

    <div class="dist-container">
        <div class="dist-card">

            <h1 class="dist-title">Inicio de Sesión</h1>

            {{-- Mensaje de error general --}}
            @if ($errors->any())
                <div class="dist-alert">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('distribuidores.login.process') }}" method="POST">
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
                    <button type="submit" class="dist-btn dist-btn-primary">Acceder</button>

                    {{-- Por ahora solo visual: lo conectamos después --}}
                    <a href="#" class="dist-btn dist-btn-secondary" style="text-align:center; line-height: 36px;">
                        Crear una cuenta
                    </a>
                </div>

                <div class="dist-forgot">
                    <a href="#">¿Has olvidado tu contraseña?</a>
                </div>
            </form>

        </div>
    </div>

</body>

</html>