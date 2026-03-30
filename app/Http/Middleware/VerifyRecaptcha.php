<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    /**
     * Middleware para validar Google reCAPTCHA en el login.
     *
     * En entorno local (APP_ENV=local) se omite la validación
     * para no bloquear el desarrollo.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // En entorno local NO forzamos el uso de reCAPTCHA
        // Esto permite desarrollar sin bloquear el login.
        if (app()->environment('local')) {
            return $next($request);
        }

        // Solo aplicamos el middleware en peticiones POST
        if ($request->isMethod('post')) {
            $captchaToken = $request->input('g-recaptcha-response');

            if (empty($captchaToken)) {
                return back()
                    ->withErrors([
                        'captcha' => 'Confirma que no eres un robot.',
                    ])
                    ->withInput();
            }

            $secretKey = env('RECAPTCHA_SECRET_KEY');

            if (empty($secretKey)) {
                return back()
                    ->withErrors([
                        'captcha' => 'Error de configuración del sistema. Contacta al administrador.',
                    ])
                    ->withInput();
            }

            try {
                $response = Http::asForm()->post(
                    'https://www.google.com/recaptcha/api/siteverify',
                    [
                        'secret' => $secretKey,
                        'response' => $captchaToken,
                        'remoteip' => $request->ip(),
                    ]
                );

                if (!$response->successful()) {
                    return back()
                        ->withErrors([
                            'captcha' => 'No se pudo verificar el captcha. Intenta nuevamente.',
                        ])
                        ->withInput();
                }

                $result = $response->json();
                $success = $result['success'] ?? false;

                if (!$success) {
                    return back()
                        ->withErrors([
                            'captcha' => 'Confirma que no eres un robot.',
                        ])
                        ->withInput();
                }
            } catch (\Throwable $e) {
                return back()
                    ->withErrors([
                        'captcha' => 'Ocurrió un error al verificar el captcha. Intenta nuevamente.',
                    ])
                    ->withInput();
            }
        }

        return $next($request);
    }
}