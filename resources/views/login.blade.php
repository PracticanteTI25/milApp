<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <!-- Título de la página -->
    <title>Iniciar sesión</title>

    <!-- Viewport para responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- Archivo CSS propio -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <!-- Contenedor principal -->
    <div class="login-container colorgris">

        <!-- Caja de login -->
        <div class="login-box">

            <h2>Iniciar sesión</h2>

            <!-- Formulario -->

            <form action="{{ route('login.process') }}" method="POST">
                @csrf <!-- Protección CSRF -->

                <!-- Correo electrónico -->
                <div class="input-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <!-- Contraseña -->
                <div class="input-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>

                <!-- reCAPTCHA -->
                <div class="input-group">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}">
                    </div>
                </div>

                <!-- Botón -->
                <button type="submit">Ingresar</button>

                <!-- Mensaje de error -->
                @if ($errors->any())
                    <p class="error">
                        {{ $errors->first() }}
                    </p>
                @endif
            </form>

            <!-- Script reCAPTCHA (UNA sola vez) -->
            <script src="https://www.google.com/recaptcha/api.js" async defer>
            </script>

        </div>
    </div>

</body>

</html>