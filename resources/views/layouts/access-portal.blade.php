<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'milApp | Acceso')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicons/apple-touch-icon.png') }}">


    {{-- Fuente --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- CSS del portal --}}
    <link rel="stylesheet" href="{{ asset('css/access-portal.css') }}?v=2">


    {{-- Fixes responsive globales --}}
    <link rel="stylesheet" href="{{ asset('css/responsive-fixes.css') }}">

    @stack('styles')
</head>

<body>

    @yield('content')

    @stack('scripts')

</body>

</html>