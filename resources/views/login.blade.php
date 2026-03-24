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

            <h2>Iniciar sesión milApp</h2>

            <!-- Formulario -->
            <form action="/login" method="POST">
                @csrf <!-- Protección CSRF -->

                <!-- Usuario -->
                <div class="input-group">
                    <label>Usuario</label>
                    <input type="text" name="user" required>
                </div>

                <!-- Contraseña -->
                <div class="input-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>

                <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

                <br>
                <!-- Botón -->
                <button type="submit">Ingresar</button>

                <!-- Mensaje de error -->
                @if ($errors->any())
                    <p class="error">{{ $errors->first() }}</p>
                @endif

            </form>

        </div>
    </div>

</body>

</html>