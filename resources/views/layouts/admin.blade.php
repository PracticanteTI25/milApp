@extends('adminlte::page')

@section('title')
@yield('title')
@stop

{{-- ✅ CSS GLOBAL --}}
@section('adminlte_css')

<!-- Favicons personalizados -->
    <link rel="icon" type="image/png" href="{{ asset('favicons/favicon-32.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicons/favicon-16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicons/apple-touch-icon.png') }}">

<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stack('css')
@stop

{{-- ✅ CONTENIDO PRINCIPAL (AdminLTE ya maneja el header) --}}
@section('content')
@yield('content')
@stop

{{-- ✅ JS GLOBAL --}}
@section('adminlte_js')
@stack('js')
@stop