<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuth\RegisteredAdminController;
use App\Http\Controllers\AdminAuth\AuthenticatedSessionController;
use App\Http\Controllers\SearchServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CertificateController;

Route::post('/register-admin', [RegisteredAdminController::class, 'store']);
Route::post('/login-admin', [AuthenticatedSessionController::class, 'store']);

Route::middleware(['auth:admin'])->group(function(){
    Route::get('/user-admin', function(Request $request){
        return $request->user()->load('church');
    });

    Route::post('/logout-admin', [AuthenticatedSessionController::class, 'destroy']);

    Route::get('/search-service/{searchStatus}/{church_id}', [SearchServiceController::class, 'searchService']);
    Route::get('/show-all-book/{church_id}', [SearchServiceController::class, 'showAllBook']);
    Route::get('/show-all-event/{church_id}', [SearchServiceController::class, 'showAllEvent']);
    Route::post('/changeStatus', [SearchServiceController::class, 'changeStatus']);

    Route::get('all-admin', [SearchServiceController::class, 'allAdmin']);

    Route::delete('delete-admin', [SearchServiceController::class, 'deleteAdmin']);
    Route::put('update-admin', [SearchServiceController::class, 'updateAdmin']);

    Route::put('/edit-profile', [UserController::class, 'updateAdmin']);

    Route::post('walkin-mass', [BookingController::class, 'walkinMass']);
    Route::post('walkin-baptism', [BookingController::class, 'walkinBaptism']);
    Route::post('walkin-wedding', [BookingController::class, 'walkinWedding']);
    Route::post('walkin-memorial', [BookingController::class, 'walkinMemorial']);
    Route::post('walkin-confirmation', [BookingController::class, 'walkinConfirmation']);


    Route::post('select-event', [BookingController::class, 'selectEvent']);
    Route::post('findEventAdded', [BookingController::class, 'findEventAdded']);

    Route::post('add-new-cert', [CertificateController::class, 'addNewCertificate']);
    Route::get('show-certificate/{status}', [CertificateController::class, 'showCert']);
});
