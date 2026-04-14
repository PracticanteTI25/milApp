<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    {{-- Favicons personalizados --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicons/apple-touch-icon.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS global -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">


    <link rel="stylesheet" href="{{ asset('css/distribuidores-login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/distribuidores.css') }}">


    @yield('css')
</head>

<body>


    <div class="dist-page">
        @yield('content')
    </div>


    @yield('js')

</body>

</html>