<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel Distribuidor')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSS principal --}}
    <link rel="stylesheet" href="{{ asset('css/points.css') }}">

    @stack('styles')
</head>

<body>

    {{-- Navbar existente --}}
    @include('distribuidores.partials.navbar')

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>