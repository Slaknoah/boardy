<?php

use App\Http\Controllers\API\AdvertController;
use App\Http\Controllers\API\TokenController;
use App\Http\Controllers\API\UserController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::get('/me', [ UserController::class, 'show'] )->name('api.me');

    Route::get('/tokens', [ TokenController::class, 'index' ])->name('api.tokens.get');
    Route::post('/tokens', [ TokenController::class, 'create' ])->name('api.tokens.create');
    Route::delete('/tokens/{tokenID}', [ TokenController::class, 'revoke' ])->name('api.tokens.revoke');

    Route::get('/adverts', [ AdvertController::class, 'index'])->name('api.adverts.get');
    Route::get('/adverts/{advert}', [ AdvertController::class, 'show'])->name('api.advert.get');
    Route::post('/adverts', [ AdvertController::class, 'store'])->name('api.adverts.create');
});
