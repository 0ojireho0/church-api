<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/book-a-service/{id}', [ChurchController::class, 'findChurch']);

    Route::post('book-baptism', [BookingController::class, 'baptismBook']);
    Route::post('book-wedding', [BookingController::class, 'weddingBook']);
    Route::post('book-memorial', [BookingController::class, 'memorialBook']);
    Route::post('book-confirmation', [BookingController::class, 'confirmationBook']);
    Route::post('book-mass', [BookingController::class, 'massBook']);
    Route::post('request-certificate', [BookingController::class, 'requestCertificate']);
    Route::post('cancel-booking', [BookingController::class, 'cancelBooking']);

    Route::get('my-booking/{user_id}', [BookingController::class, 'myBooks']);

    Route::post('/chatbot', [ChatController::class, 'processMessage']);

    Route::put('/edit-profile', [UserController::class, 'update']);


});

Route::get('/get-church', [ChurchController::class, 'index']);
Route::get('try-email', [BookingController::class, 'sendEmail']);
Route::get('book-available/{id}', [BookingController::class, 'bookAvailable']);
