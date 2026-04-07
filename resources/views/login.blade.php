<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <!-- Título de la página -->
    <title>Iniciar sesión</title>


    <!-- Favicons NUEVOS (navegador) -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}?v=3">

    <!-- Fallback recomendado (algunos navegadores lo toman) -->
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.png') }}?v=3">

    <!-- iOS (solo Apple) -->
    <link rel="apple-touch-icon" href="{{ asset('favicons/apple-touch-icon.png') }}?v=3">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- Archivo CSS propio -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="login-page">

    <!-- Contenedor principal -->
    <div class="login-container colorgris">

        <!-- Caja de login -->
        <div class="login-box">

            <div class="text-center mb-4">
                <img src="{{ asset('img/login/Logo-V2.png') }}" alt="Logo" class="login-logo">
            </div>


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
        <!-- {{-- Acceso distribuidores --}}
        <div class="distributor-access">
            <a href="{{ route('distribuidores.login') }}" class="distributor-link">
                <span class="text">
                    <strong>Distribuidores</strong><br>
                    <small>Acceso para clientas</small>
                </span>
            </a>
        </div> -->

    </div>



</body>

</html>