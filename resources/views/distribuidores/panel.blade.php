<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Distribuidores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS distribuidores -->
    <link rel="stylesheet" href="{{ asset('css/distribuidores-login.css') }}?v=3">
</head>

<body class="dist-body">

    <div class="dist-container">
        <div class="dist-card">

            <h1 class="dist-title">Panel de Distribuidores</h1>

            <p>Login correcto. Aquí irá el catálogo, puntos y canje.</p>

            <form method="POST" action="{{ route('distribuidores.logout') }}">
                @csrf
                <button class="dist-btn dist-btn-secondary" type="submit">
                    Cerrar sesión
                </button>
            </form>

        </div>
    </div>

</body>

</html>