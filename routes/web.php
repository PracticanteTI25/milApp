<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (SIN AUTENTICACIÓN)
|--------------------------------------------------------------------------
*/

// Redirigir raíz al login
Route::get('/', function () {
    return redirect('/login');
});

// Mostrar formulario de login
Route::get('/login', [AuthController::class, 'showLogin']);

// Procesar login
Route::post('/login', [AuthController::class, 'login']);

// Cerrar sesión
Route::get('/logout', [AuthController::class, 'logout']);


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (REQUIEREN LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.custom'])->group(function () {

    // Dashboard principal
    Route::get('/admin', function () {
        return view('admin');
    });

});


/*
|--------------------------------------------------------------------------
| CRUD DE USUARIOS (SOLO ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.custom', 'role:Admin'])->group(function () {

    // Listar usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->name('usuarios.index');

    Route::get('/usuarios/create', [UsuarioController::class, 'create'])
        ->name('usuarios.create');

    Route::post('/usuarios', [UsuarioController::class, 'store'])
        ->name('usuarios.store');

    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])
        ->name('usuarios.edit');

    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])
        ->name('usuarios.update');

    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])
        ->name('usuarios.destroy');
});