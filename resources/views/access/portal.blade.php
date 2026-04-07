<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>milApp | Acceso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS del portal -->
    <link rel="stylesheet" href="{{ asset('css/access-portal.css') }}?v=2">
</head>

<body>

    <div class="portal-container">

        <div class="portal-box">

            {{-- Logo --}}
            <div class="portal-logo">
                <img src="{{ asset('img/portal/logo_gris.png') }}" alt="Milagros">
            </div>

            <p class="portal-subtitle">Selecciona tu tipo de acceso</p>

            <div class="portal-cards">

                {{-- Acceso corporativo --}}
                <a href="{{ route('login') }}" class="portal-card">
                    <img src="{{ asset('img/portal/logo-V2.png') }}" class="portal-icon-img" alt="Corporativo">
                    <h3>Equipo Corporativo</h3>
                    <p>Uso interno para colaboradores</p>
                </a>

                <a href="{{ route('distribuidores.login') }}" class="portal-card">
                    <img src="{{ asset('img/portal/distribuidores.png') }}" class="portal-icon-img"
                        alt="Distribuidores">
                    <h3>Distribuidores</h3>
                    <p>Puntos y canjes</p>
                </a>

            </div>

        </div>

    </div>

</body>

</html>