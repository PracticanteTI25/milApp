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
use App\Http\Controllers\Distribuidores\PointsController;

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

        Route::get('/login/token', [DistributorAuthController::class, 'showTokenForm'])
            ->name('distribuidores.login.token.form');

        Route::post('/login/token', [DistributorAuthController::class, 'verifyToken'])
            ->name('distribuidores.login.token.verify');

        Route::post('/login/token/resend', [DistributorAuthController::class, 'resendToken'])
            ->name('distribuidores.login.token.resend');
    });

    Route::middleware('auth:distributor')->group(function () {

        Route::get('/panel', [DistributorAuthController::class, 'dashboard'])
            ->name('distribuidores.panel');


        Route::get('/puntos', [PointsController::class, 'index'])
            ->name('distribuidores.puntos');

        Route::get('/catalogo', [CatalogoController::class, 'index'])
            ->name('distribuidores.catalogo');

        // CARRITO
        Route::get('/carrito', [CartController::class, 'index'])
            ->name('distribuidores.carrito.index');

        Route::post('/carrito/agregar', [CartController::class, 'add'])
            ->name('distribuidores.carrito.add');

        Route::post('/carrito/actualizar', [CartController::class, 'updateQuantity'])
            ->name('distribuidores.carrito.update');


        Route::post('/carrito/eliminar', [CartController::class, 'remove'])
            ->name('distribuidores.carrito.remove');

        Route::post('/canje', [RedemptionController::class, 'store'])
            ->name('distribuidores.canje.store');

        Route::post('/logout', [DistributorAuthController::class, 'logout'])
            ->name('distribuidores.logout');
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

        if (!$user) {
            abort(403);
        }

        // Admin: ve todo
        if ($user->roles->contains('slug', 'admin')) {
            $enabledModules = \App\Models\Area::pluck('slug')->toArray();

            return view('admin', compact('enabledModules'));
        }

        // Usuario normal: debe tener al menos un área o rol
        if ($user->areas->isEmpty() && $user->roles->isEmpty()) {
            abort(403, 'Usuario sin áreas ni roles asignados');
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
        ->name('corporativo.index');

    // Áreas (navegación por módulos)
    Route::get('/areas/{slug}', [AreaController::class, 'show'])
        ->name('areas.show');

    /*
    |--------------------------------------------------------------------------
    | ÁREA COMERCIAL
    |--------------------------------------------------------------------------
    */

    Route::prefix('areas/comercial')->group(function () {});

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
