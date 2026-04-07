<?php

return [

    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'unique' => 'El campo :attribute ya está en uso.',

    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],

    'regex' => 'La :attribute debe incluir al menos una mayúscula y un número.',
    'confirmed' => 'La confirmación de :attribute no coincide.',

    'attributes' => [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'name' => 'nombre',
    ],

];

// Este archivo es la traduccion de idioma exclusivamente para mensajes de validacion, errores de formulario y reglas como required, email, min, etc