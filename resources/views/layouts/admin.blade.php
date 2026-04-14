@extends('adminlte::page')

@section('title')
@yield('title')
@stop

{{-- ✅ CSS GLOBAL --}}
@section('adminlte_css')
{{-- Favicons personalizados --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
<link rel="apple-touch-icon" href="{{ asset('favicons/apple-touch-icon.png') }}">

{{-- CSS propio, base del proyecto --}}
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">


{{--  Fixes responsive globales --}}
<link rel="stylesheet" href="{{ asset('css/responsive-fixes.css') }}">


@stack('css')
@stop

{{--  CONTENIDO PRINCIPAL (AdminLTE ya maneja el header) --}}
@section('content')
@yield('content')
@stop

{{--  JS GLOBAL --}}
@section('adminlte_js')
@stack('js')
@stop