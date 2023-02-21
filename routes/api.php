<?php

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

Route::group([
    'middleware' => ['api'],
], function () {
    Route::prefix('auth')->group(function () {
        Route::post('user/register', [\App\Http\Controllers\AuthUser\RegisterController::class, 'store']);
        Route::post('admin/login', [\App\Http\Controllers\AuthAdmin\SessionController::class, 'login']);
        Route::middleware(['auth:api', 'admin.access'])->group(function () {
            Route::get('admin/self', [\App\Http\Controllers\AuthAdmin\SessionController::class, 'self']);
            Route::post('admin/logout', [\App\Http\Controllers\AuthAdmin\SessionController::class, 'logout']);
        });
    });

    Route::middleware(['auth:api', 'admin.access'])->group(function () {
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index']);
        Route::get('users/{user}', [\App\Http\Controllers\UserController::class, 'show']);
        Route::post('users', [\App\Http\Controllers\UserController::class, 'store']);
        Route::put('users/{user}', [\App\Http\Controllers\UserController::class, 'update']);
        Route::delete('users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);
    });

    Route::post('user/games', [\App\Http\Controllers\Game\GameController::class, 'store']);

    Route::middleware('check.game')->group(function () {
        Route::get('user/games/{game}', [\App\Http\Controllers\Game\GameController::class, 'show']);
        Route::delete('user/games/{game}', [\App\Http\Controllers\Game\GameController::class, 'destroy']);

        Route::get('user/games/{game}/histories', [\App\Http\Controllers\Game\HistoryController::class, 'index']);
        Route::post('user/games/{game}/win', [\App\Http\Controllers\Game\WinController::class, 'store']);
    });

});
