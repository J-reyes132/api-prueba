<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!env('APP_DEBUG')) {
        return response()->json(['status' => 'error', 'message' => 'No autorizado'], ResponseCodes::UNAUTHORIZED);
    } else {
        return redirect('api/documentation');
    }
});
