<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuth\RegisteredAdminController;
use App\Http\Controllers\AdminAuth\AuthenticatedSessionController;
use App\Http\Controllers\SearchServiceController;
use Illuminate\Http\Request;

Route::post('/register-admin', [RegisteredAdminController::class, 'store']);
Route::post('/login-admin', [AuthenticatedSessionController::class, 'store']);

Route::middleware(['auth:admin'])->group(function(){
    Route::get('/user-admin', function(Request $request){
        return $request->user()->load('church');
    });

    Route::post('/logout-admin', [AuthenticatedSessionController::class, 'destroy']);

    Route::get('/search-service/{searchStatus}/{church_id}', [SearchServiceController::class, 'searchService']);
    Route::post('/changeStatus', [SearchServiceController::class, 'changeStatus']);

    Route::get('all-admin', [SearchServiceController::class, 'allAdmin']);

    Route::delete('delete-admin', [SearchServiceController::class, 'deleteAdmin']);
    Route::put('update-admin', [SearchServiceController::class, 'updateAdmin']);
});
