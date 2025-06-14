<?php

namespace App\Helpers;

use Exception;
use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Offers;
use App\Models\Requests;
use App\Models\FcmTokens;
use App\Models\Notification;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\ResponseTrait;

class Helpers
{
    use ResponseTrait;
    public static function  hasAnyPermission($user, $permissions)
    {
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }
        return false;
    }

    public static function upload_files($file, $path)
    {

        $originalName = $file->getClientOriginalName();
        $name = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
        $file->move(public_path($path), $name);
        return $path . $name;
    }
    public static function delete_file($path)
    {
        File::delete(public_path("$path"));
        return;
    }
    public static function provider_rating($provider_id)
    {
        return 0;
    }
    public static function fetchNegotiationWithValidation($offerId, $role, $user)
    {
        $offer = Offers::with(['ride_request','provider'])->findOrFail($offerId);
        $rideRequest = $offer->ride_request;
        if ($rideRequest->status !== 'pending') {
            throw new Exception('Request not available', 403);
        }
        if ($role === 'driver') {
        } elseif ($role === 'user') {
            if ($rideRequest->user_id !== $user->id) {
                throw new Exception('Not allowed', 403);
            }
        } else {
            throw new Exception('Invalid role', 400);
        }

        return [$offer, $rideRequest];
    }
    public static function push_notification($data)
    {
        $user = $data['user'];
    
        // Get the FCM tokens for the user
        $fcms = FcmTokens::where("account_id", $user->id)->where("account_type", get_class($user))->get();
        $data['fcms'] = $fcms;
        $data["trs"] = "Helpers";  // This line is not needed unless you're using it elsewhere
    
        $title = $data['title'];
        $model_type = $data['model_type'];
        $model_id = $data['model_id'];
        $description = $data['description'];
        $provider_id = $data['provider_id'] ?? null;
        $user_id = $data['user_id'] ?? null;
        $request_id = $data['request_id'] ?? null;
    
        $results = [];  // To collect responses from each notification
    
        // Loop through each FCM token and send the notification
        foreach ($fcms as $item) {
            $fcm = $item->token;
    
            // Retrieve the credentials for Firebase
            $credentialsFilePath = Http::get(asset('json/homeandcar-c3577-f16e306f717a.json')); // in server
            $client = new GoogleClient();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();
    
            $access_token = $token['access_token'];
    
            // Set the headers for Firebase Cloud Messaging (FCM)
            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            ];
    
            // Prepare the data payload for the notification
            $data = [
                "message" => [
                    "token" => $fcm,
                    "notification" => [
                        "title" => (string) $title,
                        "body" => (string) $description,
                    ],
                    "data" => [
                        "title" => (string) $title,
                        "body" => (string) $description,
                        "model_id" => (string) $model_id,
                        "model_type" => (string) $model_type,
                        "provider_id" => (string) ($provider_id ?? ""),
                        "user_id" => (string) ($user_id ?? ""),
                        "request_id" => (string) ($request_id ?? ""),
                    ],
                ]
            ];
    
            $payload = json_encode($data);
    
            // Send the notification via cURL
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
    
            // Collect the response for each notification
            if ($err) {
                $results[] = ['error' => 'Curl Error: ' . $err];
            } else {
                $results[] = ['response' => json_decode($response, true)];
            }
        }
    
        // Return a response after sending all notifications
        // return response()->json([
        //     'message' => 'Notifications have been sent',
        //     'results' => $results
        // ], 200);
    }
    
    public static function apply_coupon($coupon,$price){

        if (Carbon::now()->gt(Carbon::parse($coupon->end_at))) {
            return response()->json(['message' => 'Coupon has expired'], 400);
        }
        $discount_value=0;
        $final_price=0;

        // admin comission percentage
        $max_commission_percentage= getBusinessSetting("profit_percentage") ;
        // addmin comission amount
        $max_commission_amount =   ($price * $max_commission_percentage )/100 ;
        
        if($coupon->type=="percentage"){
            // check if coupon value bigger than comission to apply max value can user get discount
            $coupon_value=$coupon->coupon_value <= $max_commission_percentage ? $coupon->coupon_value : $max_commission_percentage ;
            $discount_value=($price * $coupon_value )/100;
            $final_price= $price - $discount_value;
        }elseif($coupon->type=="amount"){
            $discount_value =  $coupon->coupon_value <= $max_commission_amount ? $coupon->coupon_value :  $max_commission_amount ;
            $final_price= $price - $discount_value;
        }
        return [
            "final_price"=>$final_price,
            "discount_value"=>  $discount_value,
            "admin_profit"=> $max_commission_amount - $discount_value,
            "coupon_id"=>$coupon->id
        ];
    }

    public static function getScheduledRequest($account){
        $now = Carbon::now('Africa/Cairo');
        $today = $now->format('m/d/Y');
        $currentTime = $now->format('H:i');
        $oneHourAgo = $now->copy()->subHour()->format('H:i');
        $oneHourLater = $now->copy()->addHour()->format('H:i');
        $query= Requests::query();
        if(get_class($account)==="App\Modeles\User"){
            $query->where('user_id', $account->id);
        }else{
            $query->where('provider_id', $account->id);
        }
        $scheduledRequests =$query->where('date', $today)
            ->with(['user','service'])
            ->get();

        foreach ($scheduledRequests as $scheduledRequest) {
            $requestTime = Carbon::parse($scheduledRequest->time)->format('H:i');
            if ($requestTime >= $oneHourAgo && $requestTime <= $oneHourLater) {
                return $scheduledRequest;
            }
        }
        return [];

    }
    public static function get_nearest_drivers($userLat, $userLng){
        $radius = 20; // 20 km radius

        
        $drivers = DB::table('providers')
            ->select('id', 'name', 'lat', 'lng','user_id')
            // ->where('last_seen', '>=', now()->subSeconds(15))
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(lat)) 
                  * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) AS distance",
                [$userLat, $userLng, $userLat]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->limit(50)
            ->get();
    
        if ($drivers->isEmpty()) {
            throw new Exception('Not Found Drivers Nearest you', 400);
        }
        // خطوة 2: حساب المسافة الفعلية باستخدام Google Distance Matrix API
        $origins = "{$userLat},{$userLng}";
        $destinations = $drivers->pluck('lat', 'id')->map(function ($lat, $id) use ($drivers) {
            $lng = $drivers->firstWhere('id', $id)->lng;
            return "$lat,$lng";
        })->values()->implode('|');

        
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/distancematrix/json", [
            'origins' => $origins,
            'destinations' => $destinations,
            'key' => $apiKey,
            'units' => 'metric', // يستخدم الكيلو
            // 'mode'=> 'walking', // Specify the mode (walking, bicycling, transit, etc.)
            'mode' => 'driving',  

        ]);
        
        $data = $response->json();

        $finalDrivers = [];
        if (!empty($data['rows'][0]['elements'])) {
            foreach ($drivers as $index => $driver) {
                $distanceText = $data['rows'][0]['elements'][$index]['distance']['text'] ?? null;
                $distanceValue = $data['rows'][0]['elements'][$index]['distance']['value'] ?? null;
                $durationText = $data['rows'][0]['elements'][$index]['duration']['text'] ?? null; // Added duration
                $durationValue = $data['rows'][0]['elements'][$index]['duration']['value'] ?? null; // Added duration value
        
                if ($distanceValue !== null && $distanceValue <= 10000) { // 10000m = 10km
                    $finalDrivers[] = [
                        'id' => $driver->id,
                        'name' => $driver->name,
                        'lat' => $driver->lat,
                        'lng' => $driver->lng,
                        'distance' => $distanceText,
                        'duration' => $durationText, // Added duration
                        'duration_value' => $durationValue, // Added duration value
                    ];
                }
            }
        }
        return $finalDrivers;
    }

    public static function get_nearest_requests($driverLat, $driverLng){
        $radius = 20; // 20 km radius

        
        $requests = Requests::with(['user','provider'])
            ->select('id',"service_id",'current_price','date','time','status','is_reminded','provider_id' ,'pickup_lat', 'pickup_lng','dropoff_lat','dropoff_lng', 'user_id')
            ->where('status', 'pending')
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(pickup_lat)) 
                * cos(radians(pickup_lng) - radians(?)) + sin(radians(?)) * sin(radians(pickup_lat)))) AS distance",
                [$driverLat, $driverLng, $driverLat]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            
            ->limit(50)
            ->get();
        
    
        if ($requests->isEmpty()) {
            return self::Response(null,'Not Found Requests Nearest you',201);
        }
        // خطوة 2: حساب المسافة الفعلية باستخدام Google Distance Matrix API
        $origins = "{$driverLat},{$driverLng}";
        $destinations = $requests->map(function ($request) {
            return "{$request->pickup_lat},{$request->pickup_lng}";
        })->implode('|');
        
        
        
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/distancematrix/json", [
            'origins' => $origins,
            'destinations' => $destinations,
            'key' => $apiKey,
            'units' => 'metric', // يستخدم الكيلو
            // 'mode'=> 'walking', // Specify the mode (walking, bicycling, transit, etc.)
            'mode' => 'driving',  

        ]);
        
        $data = $response->json();

        $finalRequests = [];
        if (!empty($data['rows'][0]['elements'])) {
            foreach ($requests as $index => $request) {
                $distanceText = $data['rows'][0]['elements'][$index]['distance']['text'] ?? null;
                $distanceValue = $data['rows'][0]['elements'][$index]['distance']['value'] ?? null;
                $durationText = $data['rows'][0]['elements'][$index]['duration']['text'] ?? null; // Added duration
                $durationValue = $data['rows'][0]['elements'][$index]['duration']['value'] ?? null; // Added duration value
        
                if ($distanceValue !== null && $distanceValue <= 10000) { // 10000m = 10km
                    $finalRequests[] = [
                        'id' => $request->id,
                        'pickup_lat' => $request->pickup_lat,
                        'pickup_lng' => $request->pickup_lng,
                        "dropoff_lat"=> $request->dropoff_lat,
                        "dropoff_lng"=> $request->dropoff_lng,
                        "current_price"=> $request->current_price,
                        "date"=>$request->date,
                        "time"=>$request->time,
                        "status"=>$request->status,
                        "is_reminded"=>$request->is_reminded,
                        'user' => $request->user, // Assuming you want to include user info it saved as user_id
                        'provider'=>$request->provider,
                        'distance' => $distanceText,
                        'duration' => $durationText, // Added duration
                        'duration_value' => $durationValue, // Added duration value
                        "service"=>$request->service,
                    ];
                }
            }
        }
        return $finalRequests;
    }

}
