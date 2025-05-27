<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\BookingController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/get-church', [ChurchController::class, 'index']);
    Route::get('/book-a-service/{id}', [ChurchController::class, 'findChurch']);

    Route::post('book-baptism', [BookingController::class, 'baptismBook']);
    Route::post('book-wedding', [BookingController::class, 'weddingBook']);
    Route::post('book-memorial', [BookingController::class, 'memorialBook']);
    Route::post('book-confirmation', [BookingController::class, 'confirmationBook']);



});
Route::get('book-available/{id}', [BookingController::class, 'bookAvailable']);
