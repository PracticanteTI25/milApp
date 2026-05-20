<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Services\PermissionService;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CorporativoController;
use App\Http\Controllers\DistributorAuthController;
use App\Http\Controllers\Distribuidores\CatalogoController;
use App\Http\Controllers\Distribuidores\CartController;
use App\Http\Controllers\Distribuidores\RedemptionController;
use App\Http\Controllers\Distribuidores\PointsController;
use \App\Http\Controllers\Distribuidores\CheckoutController;
use App\Exports\Redenciones;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Financiera\ProductController;
use App\Http\Controllers\Comercial\ProductStockController;
use App\Http\Controllers\Comercial\DistributorGoalController;
use \App\Http\Controllers\Admin\PointSettingsController;
use \App\Http\Controllers\Admin\PointAdjustmentsController;
use \App\Http\Controllers\Admin\PointHistoryController;

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

        Route::post('/carrito/actualizar', [CartController::class, 'update'])
            ->name('distribuidores.carrito.update');

        Route::post('/carrito/eliminar', [CartController::class, 'remove'])
            ->name('distribuidores.carrito.remove');

        Route::post('/canje', [RedemptionController::class, 'store'])
            ->name('distribuidores.canje.store');

        Route::post('/logout', [DistributorAuthController::class, 'logout'])
            ->name('distribuidores.logout');

        // Ver checkout de canje
        Route::get(
            '/checkout',
            [CheckoutController::class, 'show']
        )->name('distribuidores.checkout');

        // Confirmar canje (POST)
        Route::post(
            '/checkout/confirm',
            [CheckoutController::class, 'confirm']
        )->name('distribuidores.checkout.confirm');

        Route::get(
            '/canje/confirmacion/{redencion}',
            [CheckoutController::class, 'confirmacion']
        )->name('distribuidores.canje.confirmacion');
    });
});

/*
|--------------------------------------------------------------------------
| Administrativa y financiera
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'permission:financiera.productos.crear|financiera.productos.editar'
])->prefix('areas/administrativa_financiera')->group(function () {

    Route::get('/productos', [ProductController::class, 'index'])
        ->name('financiera.productos.index');

    Route::get('/productos/crear', [ProductController::class, 'create'])
        ->name('financiera.productos.create');

    Route::post('/productos', [ProductController::class, 'store'])
        ->name('financiera.productos.store');

    Route::get('/productos/{product}/editar', [ProductController::class, 'edit'])
        ->name('financiera.productos.edit');

    Route::put('/productos/{product}', [ProductController::class, 'update'])
        ->name('financiera.productos.update');
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
    | PANEL DE CONTROL DE PUNTOS (SOLO ADMIN)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth'])->prefix('admin/puntos')->group(function () {

        Route::get('/', function () {
            return view('admin.points.dashboard');
        })->name('admin.puntos.dashboard');

        //configuracion de vencimiento
        Route::get('/configuracion', [PointSettingsController::class, 'edit'])
            ->name('admin.puntos.configuracion');

        Route::post('/configuracion', [PointSettingsController::class, 'update'])
            ->name('admin.puntos.configuracion.update');

        //ajustes manuales
        Route::get('/ajustes', [PointAdjustmentsController::class, 'index'])
            ->name('admin.puntos.ajustes');

        Route::post('/ajustes', [PointAdjustmentsController::class, 'store'])
            ->name('admin.puntos.ajustes.store');

        Route::get('/historial', [PointHistoryController::class, 'index'])
            ->name('admin.puntos.historial');
    });

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


    Route::middleware([
        'auth',
        'permission:comercial.stock.editar'
    ])->prefix('areas/comercial')->group(function () {


        Route::get('/stock', [ProductStockController::class, 'index'])
            ->name('comercial.stock.index');

        Route::get('/stock/{product}/editar', [ProductStockController::class, 'edit'])
            ->name('comercial.stock.edit');

        Route::put('/stock/{product}', [ProductStockController::class, 'update'])
            ->name('comercial.stock.update');

        Route::get('/metas', [DistributorGoalController::class, 'index'])
            ->name('comercial.metas.index');

        Route::get('/metas/{distributor}/editar', [DistributorGoalController::class, 'edit'])
            ->name('comercial.metas.edit');

        Route::post('/metas/{distributor}', [DistributorGoalController::class, 'update'])
            ->name('comercial.metas.update');
    });

    /*
    |--------------------------------------------------------------------------
    | LOGÍSTICA
    |--------------------------------------------------------------------------
    */


    Route::middleware([
        'auth',
        'permission:logistica.redenciones.exportar'
    ])->prefix('areas/logistica_distribucion')->group(function () {

        Route::get('/redenciones/excel', function () {
            return Excel::download(
                new Redenciones(),
                'pedidos_distribuidoras.xlsx'
            );
        })->name('logistica.redenciones.excel');
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
