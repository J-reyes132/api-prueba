<?php

use App\Http\Controllers\DivisaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrecioProductoController;
use App\Http\Controllers\ProductoController;
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
        Route::post('/divisa/{divisa}/update', 'update');
        Route::delete('/divisa/{divisa}/delete', 'destroy');
        Route::post('/divisa/{divisa}/toggle', 'toggle');
    });
    Route::controller(ProductoController::class)->group(function () {
        Route::get('/productos', 'index');
        Route::get('/producto/{producto}/show', 'show');
        Route::post('/producto', 'store');
        Route::post('/producto/{producto}/update', 'update');
        Route::delete('/producto/{producto}/delete', 'destroy');
        Route::post('/producto/{producto}/toggle', 'toggle');
    });

    Route::controller(PrecioProductoController::class)->group(function () {
        Route::get('/precio-productos', 'index');
        Route::get('/precio-producto/{precioProducto}/show', 'show');
        Route::post('/precio-producto', 'store');
        Route::post('/precio-producto/{precioProducto}/update', 'update');
        Route::delete('/precio-producto/{precioProducto}/delete', 'destroy');
        Route::post('/precio-producto/{precioProducto}/toggle', 'toggle');
    });

    Route::post('/logout', [LoginController::class, 'logout']);
});
