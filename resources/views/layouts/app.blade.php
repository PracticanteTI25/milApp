<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- Favicons -->
    <link rel="icon" sizes="32x32" href="{{ asset('favicons/favicon-32.png') }}">
    <link rel="icon" sizes="16x16" href="{{ asset('favicons/favicon-16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicons/apple-touch-icon.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS global -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    @yield('css')
</head>

<body>

    @yield('content')

    @yield('js')

</body>

</html>