<?php

use App\Http\Controllers\DivisaController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::controller(DivisaController::class)->group(function () {
        Route::get('/divisas', 'index');
        Route::get('/divisas/{id}', 'show');
        Route::post('/divisas', 'store');
        Route::put('/divisas/{id}', 'update');
        Route::delete('/divisas/{id}', 'destroy');
        Route::get('/divisas/toggle/{id}', 'toggle');
    });

    Route::post('/logout', [LoginController::class, 'logout']);
});
