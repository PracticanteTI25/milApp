<?php

return [

    'brand' => 'Distribuidores',

    'items' => [
        [
            'label' => 'Catálogo',
            'route' => 'distribuidores.catalogo',
        ],
        [
            'label' => 'Carrito',
            'route' => 'distribuidores.carrito.index',
            'badge' => 'cart', // indicador dinámico
        ],
        [
            'label' => 'Cerrar sesión',
            'route' => 'distribuidores.logout',
            'method' => 'POST',
        ],
    ],

];