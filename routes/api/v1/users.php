<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\V1\Users\AuthController;
use App\Http\Controllers\Api\V1\Users\ReviewsController;
use App\Http\Controllers\Api\V1\Users\RequestsController;
use App\Http\Controllers\Api\V1\Users\RequestHandymanController;

Route::prefix('auth/')->group(function(){

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// api/v1/users/auth/register
Route::middleware(['auth:sanctum','auth.user','is_verified'])->group(function(){

        Route::get('get-offers',[RequestsController::class,'get_all_offers']);
        Route::get('driver-offers',[RequestsController::class,'getTargetDriverOffers']);
        Route::post('create-request',[RequestsController::class,'create_request']);
        Route::post("accept-offer",[RequestsController::class,"accept_offer"]);
        Route::post("rejected-offer",[RequestsController::class,"rejected_offer"]);
        Route::post("add-review",[ReviewsController::class,"add_review"]);
        Route::get("requests",[RequestsController::class,"requests"]);

        Route::get('nearest-drivers',[RequestsController::class,'nearest_drivers']);


});
