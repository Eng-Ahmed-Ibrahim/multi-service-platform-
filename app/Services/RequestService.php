<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Helpers\Helpers;
use App\Models\Requests;
use App\Events\RequestEvent;
use Illuminate\Support\Facades\Http;
use App\Repositories\RequestRepository;
use App\Exceptions\CustomValidationException;
use Illuminate\Validation\ValidationException;

class RequestService
{
    private $requestRepository;
    function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }


    public function add_request($validatedData, $request)
    {
        if ($request->coupon_code) {
            $validatedData["coupon_id"] = $this->apply_coupon_code($request->coupon_code);
        }
        $validatedData["required_gender"] = $request->gender;

        unset($validatedData['gender']);
        $validatedData["dropoff_lat"] = $request->type == 'trip' ? $request->dropoff_lat : null;
        $validatedData["dropoff_lng"] = $request->type == 'trip' ? $request->dropoff_lng : null;
        $validatedData["user_id"] = $request->user()->id;
        $validatedData["attachment"] = ($request->hasFile('attachment')) ? Helpers::upload_files($request->attachment, '/uploads/requests/') : null;
        $rideRequest = $this->requestRepository->add_request($validatedData);

        $this->notify_nearest_drivers_new_request($rideRequest->pickup_lat, $rideRequest->pickup_lng, $request->type, $rideRequest);
        return $rideRequest;
    }

    private function apply_coupon_code($coupon_code)
    {
        if ($coupon_code) {
            $coupon = Coupon::where("coupon_code", $coupon_code)->first();

            if (!$coupon) {
                throw new CustomValidationException("Coupon not found");
            }

            if (Carbon::now()->gt(Carbon::parse($coupon->end_at))) {
                throw new CustomValidationException("Coupon has expired");
            }

            return $coupon->id;
        }

        throw new CustomValidationException("Coupon code is required");
    }

    private function notify_nearest_drivers_new_request($pickup_lat, $pickup_lng, $type, $rideRequest)
    {
        $nearest_drivers = $this->get_nearest_drivers($pickup_lat, $pickup_lng);

        foreach ($nearest_drivers as $driver) {
            $channel = $type == 'trip' ?  "drivers" : "handymans";
            broadcast(new RequestEvent($rideRequest, $driver["id"], $channel))->toOthers();
        }
    }
    public function get_nearest_drivers($driverLat, $driverLng)
    {
        $radius = 20; // 20 km radius    
        $requests = Requests::with(['user', 'provider'])
            ->select('id', "service_id", 'current_price', 'date', 'time', 'status', 'is_reminded', 'provider_id', 'pickup_lat', 'pickup_lng', 'dropoff_lat', 'dropoff_lng', 'user_id')
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
            // return self::Response(null,'Not Found Requests Nearest you',201);
            return [];
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
                        "dropoff_lat" => $request->dropoff_lat,
                        "dropoff_lng" => $request->dropoff_lng,
                        "current_price" => $request->current_price,
                        "date" => $request->date,
                        "time" => $request->time,
                        "status" => $request->status,
                        "is_reminded" => $request->is_reminded,
                        'user' => $request->user, // Assuming you want to include user info it saved as user_id
                        'provider' => $request->provider,
                        'distance' => $distanceText,
                        'duration' => $durationText, // Added duration
                        'duration_value' => $durationValue, // Added duration value
                        "service" => $request->service,
                    ];
                }
            }
        }
        return $finalRequests;
    }
    public  function getScheduledRequest($account)
    {
        $now = Carbon::now('Africa/Cairo');
        $today = $now->format('m/d/Y');
        $currentTime = $now->format('H:i');
        $oneHourAgo = $now->copy()->subHour()->format('H:i');
        $oneHourLater = $now->copy()->addHour()->format('H:i');
        $query = Requests::query();
        if (get_class($account) === "App\Modeles\User") {
            $query->where('user_id', $account->id);
        } else {
            $query->where('provider_id', $account->id);
        }
        $scheduledRequests = $query->where('date', $today)
            ->with(['user', 'service'])
            ->get();

        foreach ($scheduledRequests as $scheduledRequest) {
            $requestTime = Carbon::parse($scheduledRequest->time)->format('H:i');
            if ($requestTime >= $oneHourAgo && $requestTime <= $oneHourLater) {
                return $scheduledRequest;
            }
        }
        return [];
    }
    public function get_user_request($user)
    {
        $query = Requests::query();
        if ($user->role == "driver")
            $query->where("type", "trip");
        $UsersRequests = $query->with(["user"])
            ->where("user_id", $user->id)
            ->whereIn("status", ["pending", "accepted"])
            ->where(function ($q) use ($user) {
                $q->whereNull("required_gender")
                    ->orWhere("required_gender", $user->gender);
            })
            ->with(['service:id,name,name_ar,image'])
            ->orderBy("id", 'DESC')
            ->get();
        return $UsersRequests;
    }
}
