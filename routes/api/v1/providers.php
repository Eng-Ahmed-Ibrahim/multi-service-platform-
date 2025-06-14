<?php
// providers.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Providers\ApiController;
use App\Http\Controllers\Api\V1\Providers\AuthController;
use App\Http\Controllers\Api\V1\Providers\RequestController;
use Illuminate\Support\Facades\Broadcast;

Route::prefix('auth/')->group(function(){

    Route::post('register-handyman', [AuthController::class, 'register_handyman']);
    Route::post('register-driver', [AuthController::class, 'register_driver']);
    Route::post('login', [AuthController::class, 'login']);
});
Route::middleware(['auth:sanctum'])->group(function(){
    Broadcast::routes();
});
Route::get("services",[ApiController::class,"services"]);
Route::get("car-type",[ApiController::class,"car_type"]);
Route::get("brands",[ApiController::class,"brands"]);
Route::get("models",[ApiController::class,"models"]);

Route::middleware(['auth:sanctum','is_verified'])->group(function(){

    Route::get('request-offers',[RequestController::class,'request_offer']);
    Route::post("create-offer",[RequestController::class,"create_offer"]);
    Route::get("requests",[RequestController::class,"requests"]);
    Route::post('cancel-offer',[RequestController::class,"cancel_offer"]);

    Route::post("start-trip",[RequestController::class,"start_trip"]);
    Route::post("complete-trip",[RequestController::class,"complete_trip"]);
});
