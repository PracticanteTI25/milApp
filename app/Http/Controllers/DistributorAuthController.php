<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// LOGIN DISTRIBUIDORAS

class DistributorAuthController extends Controller
{
    public function showLogin()
    {
        return view('distribuidores.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Solo permite login si está activo
        $isActive = Distributor::where('email', $credentials['email'])
            ->where('active', true)
            ->exists();

        if (!$isActive) {
            return back()->withErrors([
                'email' => 'Cuenta desactivada o no existe.',
            ]);
        }

        // Intentar login con guard distributor
        if (Auth::guard('distributor')->attempt($credentials)) {
            $request->session()->regenerate();

            // Registrar último login
            Distributor::where('email', $credentials['email'])
                ->update(['last_login_at' => now()]);

            return redirect()->route('distribuidores.panel');
        }

        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('distributor')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('distribuidores.login');
    }

    public function dashboard()
    {
        return view('distribuidores.panel');
    }
}
