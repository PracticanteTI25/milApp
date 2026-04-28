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
use App\Http\Controllers\Auth\DistributorForgotPasswordController;
use App\Http\Controllers\Auth\DistributorResetPasswordController;
use App\Http\Controllers\Comercial\ProductController as ComercialProductController;
use App\Http\Controllers\Admin\ManualPointsAdjustmentController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (LOGIN INTERNO)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('access.portal');
})->name('access.portal');

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

    // LOGIN – solo invitados
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
            ->middleware('throttle:5,1')
            ->name('distribuidores.login.token.resend');

    });

    // LOGOUT
    Route::post('/logout', [DistributorAuthController::class, 'logout'])
        ->name('distribuidores.logout');

    // PANEL
    Route::get('/panel', [DistributorAuthController::class, 'dashboard'])
        ->middleware('auth:distributor')
        ->name('distribuidores.panel');

    // CATÁLOGO / CARRITO / CANJE
    Route::middleware('auth:distributor')->group(function () {

        Route::get('/catalogo', [CatalogoController::class, 'index'])
            ->name('distribuidores.catalogo');

        Route::get('/catalogo/{slug}', [CatalogoController::class, 'show'])
            ->name('distribuidores.catalogo.show');

        Route::get('/carrito', [CartController::class, 'index'])
            ->name('distribuidores.carrito.index');

        Route::post('/carrito/agregar', [CartController::class, 'add'])
            ->name('distribuidores.carrito.add');

        Route::post('/carrito/actualizar', [CartController::class, 'update'])
            ->name('distribuidores.carrito.update');

        Route::post('/carrito/eliminar', [CartController::class, 'remove'])
            ->name('distribuidores.carrito.remove');

        Route::post('/canje', [RedemptionController::class, 'store'])
            ->name('distribuidores.canje.store');

        Route::get('/canje/{order}', function (\App\Models\Order $order) {
            return view('distribuidores.canje-confirmacion', compact('order'));
        })->name('distribuidores.canje.confirmacion');
    });
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
    */



    Route::prefix('areas/comercial')->group(function () {

        Route::get('/puntos', [DistributorPointsController::class, 'index'])
            ->name('comercial.puntos.index');

        Route::post('/puntos/{id}', [DistributorPointsController::class, 'update'])
            ->name('comercial.puntos.update');

        Route::get('/puntos/{id}/historial', [DistributorPointsController::class, 'history'])
            ->name('comercial.puntos.historial');

        Route::get('/distribuidores', [DistributorAdminController::class, 'index'])
            ->name('distribuidores.index');

        Route::get('/distribuidores/create', [DistributorAdminController::class, 'create'])
            ->name('distribuidores.create');

        Route::post('/distribuidores', [DistributorAdminController::class, 'store'])
            ->name('distribuidores.store');

        Route::get('/distribuidores/{id}/edit', [DistributorAdminController::class, 'edit'])
            ->name('distribuidores.edit');

        Route::put('/distribuidores/{id}', [DistributorAdminController::class, 'update'])
            ->name('distribuidores.update');

        Route::delete('/distribuidores/{id}', [DistributorAdminController::class, 'destroy'])
            ->name('distribuidores.destroy');



        Route::get('/productos', [ComercialProductController::class, 'index'])
            ->name('comercial.productos.index');

        Route::get('/productos/create', [ComercialProductController::class, 'create'])
            ->name('comercial.productos.create');

        Route::post('/productos', [ComercialProductController::class, 'store'])
            ->name('comercial.productos.store');

        Route::get('/productos/{product}/edit', [ComercialProductController::class, 'edit'])
            ->name('comercial.productos.edit');

        Route::put('/productos/{product}', [ComercialProductController::class, 'update'])
            ->name('comercial.productos.update');

        Route::delete('/productos/{product}', [ComercialProductController::class, 'destroy'])
            ->name('comercial.productos.destroy');
    });


    Route::prefix('admin/finanzas')
        ->middleware('permission:finanzas.ajustes')
        ->group(function () {

            Route::get('/ajustes', [ManualPointsAdjustmentController::class, 'create'])
                ->name('finanzas.ajustes.create');

            Route::post('/ajustes', [ManualPointsAdjustmentController::class, 'store'])
                ->name('finanzas.ajustes.store');
        });



    Route::prefix('areas/logistica_distribucion')->group(function () {

        Route::get('/pedidos', [OrderController::class, 'index'])
            ->name('logistica.pedidos.index');

        Route::get('/pedidos/{order}', [OrderController::class, 'show'])
            ->name('logistica.pedidos.show');

        Route::get('/pedidos/{order}/pdf', [OrderController::class, 'pdf'])
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
