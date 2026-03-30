@extends('layouts.admin')

@section('title', $dashboard['title'])

@section('content_header')
<h1 class="colorgris body mb-2">
    {{ $dashboard['title'] }}
</h1>
@stop

@section('content')

<div class="card shadow-sm">
    <div class="card-body p-0">

        <div class="powerbi-wrapper">
            <iframe src="{{ $dashboard['url'] }}" allowfullscreen></iframe>
        </div>

    </div>
</div>

@stop