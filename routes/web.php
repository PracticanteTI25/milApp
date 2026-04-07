<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Services\PermissionService;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CorporativoController;
use App\Http\Controllers\DistributorAdminController;
use App\Http\Controllers\DistributorAuthController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (LOGIN INTERNO)
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('recaptcha')
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS - DISTRIBUIDORES (UI EXTERNA)
|--------------------------------------------------------------------------
*/


Route::prefix('distribuidores')->group(function () {

    // Login distribuidores (real)
    Route::get('/login', [DistributorAuthController::class, 'showLogin'])
        ->name('distribuidores.login');

    Route::post('/login', [DistributorAuthController::class, 'login'])
        ->middleware('throttle:10,1') // ✅ evita ataques de fuerza bruta
        ->name('distribuidores.login.process');

    Route::post('/logout', [DistributorAuthController::class, 'logout'])
        ->name('distribuidores.logout');

    // Panel distribuidores (protegido)
    Route::get('/panel', [DistributorAuthController::class, 'dashboard'])
        ->middleware('auth:distributor')
        ->name('distribuidores.panel');

    // (por ahora siguen siendo UI)
    Route::get('/catalogo', function () {
        return view('distribuidores.catalogo');
    })->name('distribuidores.catalogo');

    Route::get('/catalogo/{slug}', function ($slug) {
        return "Detalle del producto: {$slug} (en construcción)";
    })->name('distribuidores.catalogo.show');
});



/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (USUARIOS INTERNOS)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Panel principal
    Route::get('/admin', function (PermissionService $permissionService) {

        $user = auth()->user();

        if (!$user || !$user->role) {
            abort(403, 'Usuario sin rol asignado');
        }

        $enabledModules = $permissionService->getViewableModules($user->role->slug);

        return view('admin', [
            'enabledModules' => $enabledModules,
        ]);

    })->name('admin.dashboard');


    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])
        ->middleware('permission:reportes.ver')
        ->name('reportes.index');

    Route::get('/reportes/{id}', [ReporteController::class, 'show'])
        ->middleware('permission:reportes.ver')
        ->name('reportes.show');


    // Usuarios
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


    // Áreas (sidebar)
    Route::get('/areas/{slug}', [AreaController::class, 'show'])
        ->name('areas.show');

    Route::get('/corporativo', [CorporativoController::class, 'index'])
        ->name('corporativo.index');


    /*
    |--------------------------------------------------------------------------
    | ÁREA COMERCIAL – CRUD DISTRIBUIDORAS
    |--------------------------------------------------------------------------
    | ESTE ES EL CAMBIO CLAVE
    */

    Route::prefix('areas/comercial')->group(function () {

        Route::get('/distribuidores', [DistributorAdminController::class, 'index'])
            ->name('comercial.distribuidores.index');

        Route::get('/distribuidores/create', [DistributorAdminController::class, 'create'])
            ->name('comercial.distribuidores.create');

        Route::post('/distribuidores', [DistributorAdminController::class, 'store'])
            ->name('comercial.distribuidores.store');

        Route::get('/distribuidores/{id}/edit', [DistributorAdminController::class, 'edit'])
            ->name('comercial.distribuidores.edit');

        Route::put('/distribuidores/{id}', [DistributorAdminController::class, 'update'])
            ->name('comercial.distribuidores.update');

        Route::delete('/distribuidores/{id}', [DistributorAdminController::class, 'destroy'])
            ->name('comercial.distribuidores.destroy');
    });

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
