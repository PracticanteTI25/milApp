<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica que la petición sea POST (login)
        // y que exista el campo del captcha enviado desde el formulario
        if ($request->isMethod('post') && $request->has('g-recaptcha-response')) {

            // Se envía la validación a Google
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'), // clave secreta
                'response' => $request->input('g-recaptcha-response'), // respuesta del captcha
            ]);

            // Se convierte la respuesta en array
            $result = $response->json();

            // Si Google dice que no es válido
            if (!$result['success']) {
                // Se devuelve al login con error
                return back()->withErrors([
                    'captcha' => 'Confirma que no eres un robot'
                ])->withInput();
            }
        }

        // Si todo está bien, continúa con el login normal
        return $next($request);
    }
}