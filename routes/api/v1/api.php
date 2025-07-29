<?php

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\WalletController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\LocationsController;
use App\Http\Controllers\Api\V1\complaintsController;
use App\Http\Controllers\Api\V1\NotificationsController;
use App\Http\Controllers\Api\V1\SwitchAccountController;
use App\Http\Controllers\Api\V1\VerifyAccountController;
use App\Http\Controllers\Api\V1\ForgetPasswordController;

Route::post("send-otp",[ForgetPasswordController::class,"send_otp"]);
Route::post("verify-otp",[ForgetPasswordController::class,"verify_otp"]);
Route::post("verify-phone",[VerifyAccountController::class,"verify_phone"])->middleware("auth:sanctum");
Route::post("verify-account",[VerifyAccountController::class,"verify_account"])->middleware("auth:sanctum");
// api/v1

Route::middleware(['auth:sanctum'])->group(function(){
    Broadcast::routes();
});
Route::middleware(['auth:sanctum','is_verified'])->group(function(){

    Route::post("update-location",[LocationsController::class,"update_location"]);
    Route::post("update-password",[ForgetPasswordController::class,"update_password"]);
    Route::get("notifications",[NotificationsController::class,"get_notifications"]);
    Route::get("profile",[ProfileController::class,"profile"]);
    Route::post("update-profile",[ProfileController::class,"update_profile"]);
    Route::post("change-password",[ProfileController::class,"change_password"]);
    Route::post("update-profile-image",[ProfileController::class,"update_image"]);

    Route::post('send-complaint', [complaintsController::class, 'store']);

    Route::get("chat-messages",[ChatController::class,"get_messages"]);
    Route::post("send-message",[ChatController::class,"send_message"]);
    Route::get('/privacy-policy',[SettingsController::class,"privacy_policy"]);
    Route::get('/terms',[SettingsController::class,"terms"]);
    Route::post("cancel-request",[ApiController::class,"cancel_request"]);
    Route::get("cancelation-reasons",[ApiController::class,"cancelation_reasons"]);
    Route::get("request-details",[ApiController::class,"request_details"]);
    Route::get("history",[ApiController::class,"history"]);
    Route::get("reviews",[ApiController::class,"getProviderReviews"]);
    Route::get("coupons",[ApiController::class,"coupons"]);
    Route::post("logout",[ApiController::class,"logout"]);

    Route::post("switch-account",[SwitchAccountController::class,'switch_account']);
    Route::prefix("wallet")->group(function(){
        Route::get("balance",[WalletController::class,"balance"]);
        Route::get("transactions",[WalletController::class,"transactions"]);
        Route::post("deposit",[WalletController::class,"deposit"]);
    });
});
