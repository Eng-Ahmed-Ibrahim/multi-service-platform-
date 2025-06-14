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


Route::view("test","test")->name("test");
Route::get("test-reverb",function(){
    broadcast(new TestReverbEvent("Hello world ,test broadcast"));
    return "done";
});

Route::get("/test-notification",function(){
 
        $fcm = "fcMJ9t31QpKbLG5aj671dc:APA91bEBLMU7nTSHuqcCPJO90Kcd1oL8hEv9ghW7O3qv2U-DS76WVexJZzYozvQxV0bOQtWByJKQ7T1N92mtBwx3RvO2oNf6hX2n-reQn6nNJPbnMnN6Xxk";

        $credentialsFilePath = Http::get(asset('json/homeandcar-c3577-f16e306f717a.json')); // in server
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => (string) "Test",
                    "body" => (string) "Test",
                ],
                "data" => [
                    "title" => (string) "Test",
                    "body" => (string) "Test",
                    "model_id" => (string) "1",
                    "model_type" => (string) "Test",
                ],
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/homeandcar-c3577/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    
});
Route::get("test-ftp",function(){
    return "test ftp";
});
        
Route::get("/test",function(){
    $apiKey =  env('GOOGLE_MAPS_API_KEY');

    $lat=30.0444;
    $lng=31.2357;
    $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
        'latlng' => "$lat,$lng",
        'key' => $apiKey,
    ]);

    $data = $response->json();

    if (!empty($data['results']) && $data['status'] === 'OK') {
        return $data['results'][0]['formatted_address'];
    }

    return 'Address not found';
});