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
        Route::get('/divisa/{divisa}/show', 'show');
        Route::post('/divisa', 'store');
        Route::post('/divisa/{id}/update', 'update');
        Route::delete('/divisa/{divisa}/delete', 'destroy');
        Route::post('/divisa/{divisa}/toggle', 'toggle');
    });

    Route::post('/logout', [LoginController::class, 'logout']);
});
