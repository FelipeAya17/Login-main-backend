<?php

use App\Http\Controllers\api\AuthApiController;
use App\Http\Controllers\api\TercerosController;
use App\Http\Controllers\api\TiendasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/ingreso', [AuthApiController::class, 'authenticate']);
    
Route::post('/registro', [AuthApiController::class, 'register']);

Route::group(['prefix' => '/maestros'], function () {
    Route::prefix('/tiendas')->group(function () {
        Route::get('/lista', [TiendasController::class, 'listData']);
        Route::prefix('/{usuario_id}')->group(function () {
            Route::get('/usuario', [TiendasController::class, 'listDataByUsuario']);
        });
    });
});

Route::prefix('/terceros')->group(function () {
    Route::get('/', [TercerosController::class, 'listData']);
    Route::post('/crear', [TercerosController::class, 'saveOrUpdateData']);
    Route::prefix('/{tercero}')->group(function () {
        Route::get('/', [TercerosController::class, 'dataById']);
        Route::put('/editar', [TercerosController::class, 'saveOrUpdateData']);
        Route::prefix('/direcciones')->group(function () {
            Route::get('/', [TercerosController::class, 'dataDireccionesByTercero']);
            Route::post('/', [TercerosController::class, 'saveDataDireccionTercero']);
        });
    });
});
