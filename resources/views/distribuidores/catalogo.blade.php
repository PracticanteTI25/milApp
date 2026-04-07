<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo - Distribuidores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{ asset('css/distribuidores-catalogo.css') }}?v=1
</head>

<body>

    <div class="cat-wrap">
        <h1 class="cat-title">Catálogo</h1>

        <div class="cat-grid">

            {{-- Ejemplo hardcodeado por ahora (luego lo conectamos a BD) --}}
            @php
                $productos = [
                    [
                        'name' => 'DOYPACK MASCARILLA HERBAL 100 GR',
                        'slug' => 'doypack-mascarilla-herbal-100-gr',
                        'points' => 892,
                        'image' => asset('img/catalogo/demo-producto.png'), // pon una imagen demo
                    ],
                ];
            @endphp

            @foreach($productos as $p)
                {{ route('distribuidores.catalogo.show', $p['slug']) }}
                <div class="product-card">

                    <div class="product-image">
                        {{ $p['image'] }}
                    </div>

                    <div class="product-body">
                        <div class="product-name">{{ $p['name'] }}</div>
                        <div class="product-points">{{ $p['points'] }}</div>

                        <div class="product-action">
                            AÑADIR AL CARRITO →
                        </div>
                    </div>

                </div>
                </a>
            @endforeach

        </div>
    </div>

</body>

</html>