<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * LOGIN CORPORATIVO
 * Controlador de Autenticación
 *
 * Maneja el login real de usuarios del sistema.
 * NO maneja permisos (eso lo hace el middleware).
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Procesa el login del usuario.
     *
     * Seguridad:
     * - Valida credenciales
     * - Usa Auth::attempt (hash seguro)
     * - Regenera sesión (previene session fixation)
     */
    public function login(Request $request)
    {
        // Validación básica (OWASP: evita inputs inválidos)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticación real
        if (Auth::attempt($credentials)) {

            // Regenerar sesión por seguridad
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // Credenciales incorrectas
        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos',
        ]);
    }

    /**
     * Cierra sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidar sesión
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}