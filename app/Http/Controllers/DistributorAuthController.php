<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\AuthorizedDistributor;
use App\Models\DistributorLoginToken;
use App\Mail\DistributorLoginTokenMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class DistributorAuthController extends Controller
{
    /*
    | PANTALLA 1 – INGRESAR CORREO
    */

    public function showEmailForm()
    {
        return view('distribuidores.auth.login-email');
    }

    //envia token
    public function sendToken(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;

        // Verificación interna (NO se expone al usuario)
        $authorized = AuthorizedDistributor::where('email', $email)
            ->where('active', true)
            ->exists();

        if ($authorized) {

            $token = random_int(100000, 999999);

            // Invalidar tokens anteriores
            DistributorLoginToken::where('email', $email)
                ->whereNull('used_at')
                ->delete();

            // Crear token nuevo
            DistributorLoginToken::create([
                'email' => $email,
                'token_hash' => Hash::make($token),     //no guarda token real, guarda un hash
                'expires_at' => now()->addMinutes(5),
            ]);

            //se le envia el codigo al usuario
            Mail::to($email)->send(
                new DistributorLoginTokenMail($token)
            );
        }

        // Guardar email en sesión 
        session(['login_email' => $email]);

        session([
            'token_last_sent_at' => now()->timestamp,
        ]);

        return redirect()
            ->route('distribuidores.login.token.form')
            ->with('status', 'Si el correo está registrado, recibirás un código de acceso.');

    }

    /*
    | PANTALLA 2 – INGRESAR TOKEN
    */

    public function showTokenForm()
    {
        if (!session()->has('login_email')) {
            return redirect()->route('distribuidores.login');
        }

        $lastSentAt = session('token_last_sent_at');
        $cooldown = 30; // segundos

        $remaining = 0;
        if ($lastSentAt) {
            $elapsed = now()->timestamp - $lastSentAt;
            $remaining = max(0, $cooldown - $elapsed);
        }

        return view('distribuidores.auth.login-token', [
            'resendRemaining' => $remaining,
        ]);
    }

    //verificacion del codigo
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => ['required', 'digits:6'],
        ]);

        $email = session('login_email');

        if (!$email) {
            return redirect()->route('distribuidores.login');
        }

        $record = DistributorLoginToken::where('email', $email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        $success = $record && Hash::check($request->token, $record->token_hash);

        \Log::info('Distributor login attempt', [
            'email' => $email,
            'success' => $success,
            'ip' => request()->ip(),
        ]);

        if (!$success) {
            return back()->withErrors([
                'token' => 'Código inválido o expirado.',
            ]);
        }

        // Marcar token como usado
        $record->update(['used_at' => now()]);

        // Buscar o crear distribuidora
        $distributor = Distributor::firstOrCreate(
            ['email' => $email],
            ['active' => true]
        );

        Auth::guard('distributor')->login($distributor);

        session()->forget('login_email');

        return redirect()->route('distribuidores.panel');
    }


    /*
    | LOGOUT
    */

    public function logout(Request $request)
    {
        Auth::guard('distributor')->logout();       //cerrar sesion

        //evita ataques
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('distribuidores.login');
    }

    /*
    | PANEL
    */

    public function dashboard()
    {
        return view('distribuidores.panel');
    }

    public function resendToken(Request $request)
    {
        $email = session('login_email');

        if (!$email) {
            return redirect()->route('distribuidores.login');
        }

        $authorized = AuthorizedDistributor::where('email', $email)
            ->where('active', true)
            ->exists();

        if ($authorized) {

            $token = random_int(100000, 999999);

            // Invalidar tokens anteriores
            DistributorLoginToken::where('email', $email)
                ->whereNull('used_at')
                ->delete();

            DistributorLoginToken::create([
                'email' => $email,
                'token_hash' => Hash::make($token),
                'expires_at' => now()->addMinutes(5),
            ]);

            Mail::to($email)->send(
                new DistributorLoginTokenMail($token)
            );
        }

        session([
            'token_last_sent_at' => now()->timestamp,
        ]);

        return back()->with('status', 'Si el correo está registrado, recibirás un nuevo código.');

    }

}