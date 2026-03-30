<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Services\PermissionService;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

// Redirigir raíz al login
Route::redirect('/', '/login');

// Mostrar formulario de login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

// Procesar login (autenticación REAL)
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('recaptcha')
    ->name('login.process');

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (USUARIOS AUTENTICADOS)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PANEL PRINCIPAL
    |--------------------------------------------------------------------------
    | - Requiere solo autenticación
    | - Calcula módulos habilitados según el ROL REAL del usuario
    | - Envía $enabledModules a la vista admin.blade.php
    */
    Route::get('/admin', function (PermissionService $permissionService) {

        $user = auth()->user();

        // Seguridad extra: usuario sin rol no puede continuar
        if (!$user || !$user->role) {
            abort(403, 'Usuario sin rol asignado');
        }

        // Módulos habilitados para el rol del usuario
        $enabledModules = $permissionService->getViewableModules(
            $user->role->slug
        );

        return view('admin', [
            'enabledModules' => $enabledModules,
        ]);

    })->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | REPORTES
    |--------------------------------------------------------------------------
    | Protegidos por permiso reportes.ver
    */
    Route::get('/reportes', [ReporteController::class, 'index'])
        ->middleware('permission:reportes.ver')
        ->name('reportes.index');

    Route::get('/reportes/{id}', [ReporteController::class, 'show'])
        ->middleware('permission:reportes.ver')
        ->name('reportes.show');

    /*
    |--------------------------------------------------------------------------
    | CRUD DE USUARIOS (ADMINISTRACIÓN)
    |--------------------------------------------------------------------------
    | Protegido completamente por permisos
    */
    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->middleware('permission:usuarios.ver')
        ->name('usuarios.index');

    Route::get('/usuarios/create', [UsuarioController::class, 'create'])
        ->middleware('permission:usuarios.crear')
        ->name('usuarios.create');

    Route::post('/usuarios', [UsuarioController::class, 'store'])
        ->middleware('permission:usuarios.crear')
        ->name('usuarios.store');

    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])
        ->middleware('permission:usuarios.editar')
        ->name('usuarios.edit');

    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])
        ->middleware('permission:usuarios.editar')
        ->name('usuarios.update');

    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])
        ->middleware('permission:usuarios.eliminar')
        ->name('usuarios.destroy');
});

/*
|--------------------------------------------------------------------------
| RUTAS SOLO PARA DESARROLLO
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    Route::get('/db-test', function () {
        return DB::connection()->getDatabaseName();
    })->name('debug.db-test');
}