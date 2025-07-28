<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\ModelsController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\DriversController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\HandymanController;
use App\Http\Controllers\Admin\RequestsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\BusinessSettingController;
use App\Http\Controllers\Admin\DriversServicesController;
use App\Http\Controllers\Admin\HandymanServicesController;



 
    Route::middleware('guest:admin')->group(function () {
        Route::get("login",[AuthController::class,"login_form"])->name("login");
        Route::post("login",[AuthController::class,"login"]);
    });

    // Admin routes - Protected by 'auth:admin' middleware
    Route::middleware(['auth:admin'])->group(function () {
        Route::post("logout",[AuthController::class,"logout"])->name("logout");

        Route::get("/dashboard",[DashboardController::class,"index"])->name("dashboard");
        Route::view("/create",'admin.drivers.create')->name("create");
        Route::get('/roles',[RolesController::class,'index'])->middleware('can:view roles')->name('roles.index');
        Route::post('/add-role',[RolesController::class,'store'])->middleware('can:create roles')->name('roles.store');
        Route::get('/edit-role/{id}',[RolesController::class,'edit'])->middleware('can:view roles')->name('roles.edit');
        Route::post('/update-role',[RolesController::class,'update'])->middleware('can:update roles')->name('roles.update');

        Route::get("/admins",[AdminController::class,"index"])->middleware('can:view admins')->name("admins.index");
        Route::post("/admins/add-admin",[AdminController::class,"store"])->middleware('can:create admins')->name("admins.store");
        Route::post("/admins/update-admin",[AdminController::class,"update"])->middleware('can:update admins')->name("admins.update");
        Route::post("/admins/delete-admin/{admin_id}",[AdminController::class,"delete"])->middleware('can:delete admins')->name("admins.delete");

        Route::get("/users",[UserController::class,"index"])->middleware('can:view users')->name("users.index");
        Route::get("/users/{user_id}",[UserController::class,"show"])->middleware('can:view users')->name("users.show");
        Route::post("/users/add-user",[UserController::class,"store"])->middleware('can:create users')->name("users.store");
        Route::post("/users/update-user",[UserController::class,"update"])->middleware('can:update users')->name("users.update");
        Route::post("/users/delete-user/{user_id}",[UserController::class,"delete"])->middleware('can:delete users')->name("users.delete");

        Route::get("/drivers",[DriversController::class,"index"])->middleware('can:view drivers')->name("drivers.index");
        Route::get("/drivers/create",[DriversController::class,"create"])->middleware('can:create drivers')->name("drivers.create");
        Route::get("/drivers/edit/{id}",[DriversController::class,"edit"])->middleware('can:update drivers')->name("drivers.edit");
        Route::get("/drivers/{driver_id}",[DriversController::class,"show"])->middleware('can:view drivers')->name("drivers.show");
        Route::post("/drivers/add-driver",[DriversController::class,"store"])->middleware('can:create drivers')->name("drivers.store");
        Route::post("/drivers/accepted-driver/{driver_id}",[DriversController::class,"accept_driver"])->middleware('can:view drivers')->name("drivers.accept_driver");
        Route::post("/drivers/rejected-driver/{driver_id}",[DriversController::class,"reject_driver"])->middleware('can:view drivers')->name("drivers.reject_driver");
        Route::put("/drivers/update-driver/{id}",[DriversController::class,"update"])->middleware('can:update drivers')->name("drivers.update");
        Route::post("/drivers/delete-driver/{driver_id}",[DriversController::class,"delete"])->middleware('can:delete drivers')->name("drivers.delete");
        Route::post('/drivers/change-status',[DriversController::class,"change_process_status"])->middleware('can:view drivers')->name("drivers.change_process_status");
        
        
        Route::get("/handymans",[HandymanController::class,"index"])->middleware('can:view handymans')->name("handymans.index");
        Route::get("/handymans/create",[HandymanController::class,"create"])->middleware('can:create handymans')->name("handymans.create");
        Route::get("/handymans/{handyman_id}",[HandymanController::class,"show"])->middleware('can:view handymans')->name("handymans.show");
        Route::post("/handymans/add-user",[HandymanController::class,"store"])->middleware('can:create handymans')->name("handymans.store");
        Route::post("/handymans/update-user",[HandymanController::class,"update"])->middleware('can:update handymans')->name("handymans.update");
        Route::post("/handymans/delete-user/{user_id}",[HandymanController::class,"delete"])->middleware('can:delete handymans')->name("handymans.delete");

        Route::prefix("services")->name('services.')->group(function () { 
            Route::controller(ServicesController::class)->group(function () {
                Route::get("/{section}", "index")->name("index");
                Route::post("/add-service", "store")->name("store");
                Route::post("/update-service", "update")->name("update");
                Route::delete("/delete-service/{service_id}", "delete")->name("delete");
                Route::post("/update-status", "update_status")->name("update_status");
            });
        
            Route::prefix("brand")->controller(BrandsController::class)->group(function () {
                Route::get("/brands/{service_id}", "index")->name("brands");
                Route::post("/add-brand", "store")->name("brand.store");
                Route::post("/update-brand", "update")->name("brand.update");
                Route::post("/delete-brand", "delete")->name("brand.delete");
                Route::post("/update-status", "update_status")->name("brand.update_status");
                
            });
            
            Route::prefix("models")->controller(ModelsController::class)->group(function () {
                Route::get("/{brand_id}", "index")->name("models");
                Route::post("/add-model", "store")->name("model.store");
                Route::post("/update-model", "update")->name("model.update");
                Route::post("/delete-model", "delete")->name("model.delete");
                Route::post("/update-status", "update_status")->name("model.update_status");
            });
        });
        

        Route::prefix("notifications")->name('notifications.')->group(function () {
            Route::controller(NotificationController::class)->group(function () {
                Route::get("/", "index")->name("index");
                Route::post("/add-notification", "store")->name("store");
                Route::put("/update-notification", "update")->name("update");
                Route::delete("/delete-notification/{id}", "delete")->name("delete");
            });
        });
        Route::prefix("coupons")->name('coupons.')->group(function () {
            Route::controller(CouponsController::class)->group(function () {

                Route::get('/',[CouponsController::class,'index'])->middleware('can:show coupons')->name('index');
                Route::post('/add-coupon',[CouponsController::class,'store'])->middleware('can:add coupons')->name('add_coupon');
                Route::post('/delete-coupon',[CouponsController::class,'destroy'])->middleware('can:delete coupons')->name('delete_coupon');
                Route::post('/update-coupon',[CouponsController::class,'update'])->middleware('can:edit coupons')->name('update_coupon');
            });
        });
        

        Route::get("/requests/{type}",[RequestsController::class,'index'])->name('requests.index');
        Route::get("/request-details/{request_id}",[RequestsController::class,'show'])->name('requests.show');

        Route::get('/settings',[BusinessSettingController::class,'index'])->name("settings.index");
        Route::post('/settings/update',[BusinessSettingController::class,'update'])->name("settings.update");
        Route::get("/profile/{id}",[ProfileController::class,'index'])->name('profile');
        Route::post('/update-profile/{id}',[ProfileController::class,'update_profile'])->name('profile.update');
        Route::post("/update-password/{id}",[ProfileController::class,'update_password'])->name('profile.update_password');
        

        Route::get("/change-language/{lang}",[AdminController::class,"change_language"])->name("change_language");
    });


