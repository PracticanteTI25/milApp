<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SOLO tu CSS limpio --}}
    <link rel="stylesheet" href="{{ asset('css/devoluciones.css') }}">
</head>

<body style="background:#f1f5f9; margin:0; font-family: Arial, sans-serif;">

    @yield('content')

</body>

</html>