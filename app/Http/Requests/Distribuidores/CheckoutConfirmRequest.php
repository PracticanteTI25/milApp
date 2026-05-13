<?php

namespace App\Http\Requests\Distribuidores;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutConfirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo distribuidores autenticados
        return auth('distributor')->check();
    }

    public function rules(): array
    {
        return [
            // Dirección seleccionada (vendrá de API / BD)
            'direccion_id' => ['required', 'integer'],

            // Confirmación explícita
            'confirmar' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'direccion_id.required' => 'Debes seleccionar una dirección de entrega.',
            'confirmar.accepted' => 'Debes confirmar el canje para continuar.',
        ];
    }
}
