<?php

use Carbon\Carbon;
use App\Models\Offers;
use App\Helpers\Helpers;

use App\Models\Requests;
use App\Models\FcmTokens;
use App\Models\Notification;
use Google\Client as GoogleClient;
use App\Events\TestReverbEvent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;


Route::middleware('guest:admin')->group(function () {

Route::get("admin/login",[AuthController::class,"login_form"])->name("login");
Route::get("/",[AuthController::class,"login_form"]);
Route::get("login",[AuthController::class,"login_form"])->name("login");
Route::post("login",[AuthController::class,"login"]);
});


        
