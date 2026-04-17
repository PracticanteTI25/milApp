@extends('layouts.app')

@include('distribuidores.partials.navbar')

@section('title', 'Catálogo de productos')

@section('content')

    <h1 class="catalog-page-title">Catálogo de productos</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->has('cart'))
        <div class="alert alert-danger">
            {{ $errors->first('cart') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="alert alert-info">
            No hay productos disponibles para canje en este momento
        </div>
    @else

        <div class="catalog-grid">
            @foreach($products as $product)
                <div class="catalog-card">

                    {{-- Imagen --}}
                    <div class="catalog-image">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                        @else
                            <div class="catalog-image-placeholder">
                                Sin imagen
                            </div>
                        @endif
                    </div>

                    {{-- Información --}}
                    <div class="catalog-body">
                        <h3 class="catalog-title">{{ $product->name }}</h3>

                        @if($product->presentation)
                            <p class="catalog-presentation">
                                {{ $product->presentation }}
                            </p>
                        @endif

                        <div class="catalog-meta">
                            <div class="catalog-points">
                                {{ $product->currentPrice->points }} puntos
                            </div>
                            <div class="catalog-stock">
                                Stock: {{ $product->stock }}
                            </div>
                        </div>


                        {{-- Acción --}}
                        <form method="POST" action="{{ route('distribuidores.carrito.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">

                            <button type="submit" class="catalog-btn">
                                Agregar al carrito
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>

    @endif

@endsection