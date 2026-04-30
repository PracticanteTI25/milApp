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
use App\Http\Controllers\Commercial\DistributorPointsController;
use App\Http\Controllers\Distribuidores\CatalogoController;
use App\Http\Controllers\Distribuidores\CartController;
use App\Http\Controllers\Distribuidores\RedemptionController;
use App\Http\Controllers\Logistica\OrderController;
use App\Http\Controllers\Comercial\ProductController as ComercialProductController;
use App\Http\Controllers\Admin\ManualPointsAdjustmentController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('access.portal'))->name('access.portal');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('recaptcha')
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS DISTRIBUIDORES
|--------------------------------------------------------------------------
*/

Route::prefix('distribuidores')->group(function () {

    Route::middleware('guest:distributor')->group(function () {
        Route::get('/login', [DistributorAuthController::class, 'showEmailForm'])
            ->name('distribuidores.login');

        Route::post('/login', [DistributorAuthController::class, 'sendToken'])
            ->middleware('throttle:5,1')
            ->name('distribuidores.login.token');
    });

    Route::middleware('auth:distributor')->group(function () {

        Route::get('/panel', [DistributorAuthController::class, 'dashboard'])
            ->name('distribuidores.panel');

        Route::get('/catalogo', [CatalogoController::class, 'index'])
            ->name('distribuidores.catalogo');

        Route::post('/canje', [RedemptionController::class, 'store'])
            ->name('distribuidores.canje.store');
    });
});

/*
|--------------------------------------------------------------------------
| RUTAS INTERNAS (USUARIOS INTERNOS)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PANEL ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/admin', function (PermissionService $permissionService) {

        $user = auth()->user();

        if (!$user || !$user->role) {
            abort(403, 'Usuario sin rol asignado');
        }

        $enabledModules = $permissionService->getViewableModulesForUser($user);

        return view('admin', compact('enabledModules'));
    })->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | REPORTES
    |--------------------------------------------------------------------------
    */

    Route::get('/reportes', [ReporteController::class, 'index'])
        ->middleware('permission:reportes.ver')
        ->name('reportes.index');

    Route::get('/reportes/{id}', [ReporteController::class, 'show'])
        ->middleware('permission:reportes.ver')
        ->name('reportes.show');

    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | CORPORATIVO
    |--------------------------------------------------------------------------
    */

    Route::get('/corporativo', [CorporativoController::class, 'index'])
        ->middleware('permission:corporativo.ver')
        ->name('corporativo.index');

    // Áreas (navegación por módulos)
    Route::get('/areas/{slug}', [AreaController::class, 'show'])
        ->name('areas.show');

    /*
    |--------------------------------------------------------------------------
    | ÁREA COMERCIAL
    |--------------------------------------------------------------------------
    */

    Route::prefix('areas/comercial')->group(function () {

        // PUNTOS
        Route::get('/puntos', [DistributorPointsController::class, 'index'])
            ->middleware('permission:comercial.puntos.ver')
            ->name('comercial.puntos.index');

        Route::post('/puntos/{id}', [DistributorPointsController::class, 'update'])
            ->middleware('permission:comercial.puntos.editar')
            ->name('comercial.puntos.update');

        Route::get('/puntos/{id}/historial', [DistributorPointsController::class, 'history'])
            ->middleware('permission:comercial.puntos.ver')
            ->name('comercial.puntos.historial');

        // DISTRIBUIDORES
        Route::get('/distribuidores', [DistributorAdminController::class, 'index'])
            ->middleware('permission:comercial.distribuidores.ver')
            ->name('distribuidores.index');

        Route::get('/distribuidores/create', [DistributorAdminController::class, 'create'])
            ->middleware('permission:comercial.distribuidores.crear')
            ->name('distribuidores.create');

        Route::post('/distribuidores', [DistributorAdminController::class, 'store'])
            ->middleware('permission:comercial.distribuidores.crear')
            ->name('distribuidores.store');

        Route::get('/distribuidores/{id}/edit', [DistributorAdminController::class, 'edit'])
            ->middleware('permission:comercial.distribuidores.editar')
            ->name('distribuidores.edit');

        Route::put('/distribuidores/{id}', [DistributorAdminController::class, 'update'])
            ->middleware('permission:comercial.distribuidores.editar')
            ->name('distribuidores.update');

        Route::delete('/distribuidores/{id}', [DistributorAdminController::class, 'destroy'])
            ->middleware('permission:comercial.distribuidores.eliminar')
            ->name('distribuidores.destroy');

        // PRODUCTOS
        Route::get('/productos', [ComercialProductController::class, 'index'])
            ->middleware('permission:comercial.productos.ver')
            ->name('comercial.productos.index');

        Route::get('/productos/create', [ComercialProductController::class, 'create'])
            ->middleware('permission:comercial.productos.crear')
            ->name('comercial.productos.create');

        Route::post('/productos', [ComercialProductController::class, 'store'])
            ->middleware('permission:comercial.productos.crear')
            ->name('comercial.productos.store');

        Route::get('/productos/{product}/edit', [ComercialProductController::class, 'edit'])
            ->middleware('permission:comercial.productos.editar')
            ->name('comercial.productos.edit');

        Route::put('/productos/{product}', [ComercialProductController::class, 'update'])
            ->middleware('permission:comercial.productos.editar')
            ->name('comercial.productos.update');

        Route::delete('/productos/{product}', [ComercialProductController::class, 'destroy'])
            ->middleware('permission:comercial.productos.eliminar')
            ->name('comercial.productos.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | FINANZAS
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin/finanzas')
        ->middleware('permission:finanzas.ajustes')
        ->group(function () {

            Route::get('/ajustes', [ManualPointsAdjustmentController::class, 'create'])
                ->name('finanzas.ajustes.create');

            Route::post('/ajustes', [ManualPointsAdjustmentController::class, 'store'])
                ->name('finanzas.ajustes.store');
        });

    /*
    |--------------------------------------------------------------------------
    | LOGÍSTICA
    |--------------------------------------------------------------------------
    */

    Route::prefix('areas/logistica_distribucion')->group(function () {

        Route::get('/pedidos', [OrderController::class, 'index'])
            ->middleware('permission:logistica.pedidos.ver')
            ->name('logistica.pedidos.index');

        Route::get('/pedidos/{order}', [OrderController::class, 'show'])
            ->middleware('permission:logistica.pedidos.ver')
            ->name('logistica.pedidos.show');

        Route::get('/pedidos/{order}/pdf', [OrderController::class, 'pdf'])
            ->middleware('permission:logistica.pedidos.ver')
            ->name('logistica.pedidos.pdf');
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
