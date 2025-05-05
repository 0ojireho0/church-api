<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurchController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/get-church', [ChurchController::class, 'index']);
    Route::get('/book-a-service/{id}', [ChurchController::class, 'findChurch']);

});

