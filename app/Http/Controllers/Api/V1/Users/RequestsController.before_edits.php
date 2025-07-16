<?php

namespace App\Http\Controllers\Api\V1\Users;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Offers;
use App\Helpers\Helpers;
use App\Models\Requests;
use App\Models\ChatRooms;
use App\Models\FcmTokens;
use App\Events\RequestEvent;
use Illuminate\Http\Request;
use App\Events\OfferStatusEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

class RequestsController extends Controller
{
    use ResponseTrait;
        public function requests(Request $request)
    {


        $scheduleRequest = Helpers::getScheduledRequest($request->user());
        if (! empty($scheduleRequest)) {
            $data = [
                "is_scheduled" => 1,
                "requests" => array($scheduleRequest),
            ];
            return $this->Response($data, "You Have A Schedule Request , Be Ready", 201);
        }
        $query = Requests::query();
        if ($request->user()->role == "driver")
            $query->where("type", "trip");
        $UsersRequests = $query->with(["user"])
            ->whereIn("status", ["pending", "accepted"])
            ->where(function ($q) use ($request) {
                $q->whereNull("required_gender")
                    ->orWhere("required_gender", $request->user()->gender);
            })
            ->where("user_id", $request->user()->id  )
            
            ->with(['service:id,name,name_ar,image'])
            ->orderBy("id", 'DESC')
            ->get();
        $data = [
            "is_scheduled" => 0,
            "requests" => $UsersRequests,
        ];
        return $this->Response($data, __('messages.Requests'), 201);
    }
    public function get_all_offers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required|exists:requests,id",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Validation Error", 422);
        $offers = Offers::with('provider', 'request.service')->orderBy('id', 'desc')->where("request_id", $request->request_id)->get()->groupBy('provider_id');
        $result = $offers->map(function ($offerGroup, $providerId) {
            $provider = $offerGroup->first()->provider;
            $provider["rating"] = 0;

            $last_offer = $offerGroup->first();
            $service = [
                "id" => $offerGroup[0]['request']['service']["id"],
                "name" => $offerGroup[0]['request']['service']["name"],
                "name_ar" => $offerGroup[0]['request']['service']["name_ar"],
                "image" => $offerGroup[0]['request']['service']["image"],
            ];
            return [
                "id" => $offerGroup->first()->id,
                'provider' => $provider,
                'service' => $service,
                'last_offer_price' => number_format($last_offer->proposed_price, 2),  // Format the last offer price
            ];
        })->values()->toArray();
        return $this->Response($result, "Offers", 201);
    }


    public function create_request(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'pickup_lat'    => 'required|numeric|between:-90,90',
            'pickup_lng'    => 'required|numeric|between:-180,180',
            'dropoff_lat'   => 'nullable|numeric|between:-90,90',
            'dropoff_lng'   => 'nullable|numeric|between:-180,180',
            'current_price' => 'required|numeric|min:0',
            'type' => 'required|in:trip,home_service,car_service',
            "description" => "nullable",
            "date" => "nullable|date|date_format:m/d/Y|after_or_equal:today",
            "time" => "nullable",
            "attachment" => "nullable|file",
            'gender' => 'nullable|in:male,female',
            "service_id" => "required|exists:services,id",
            "payment_type" => "required|in:wallet,cash,credit_card",
        ]);
        if ($validated->fails())
            return $this->Response($validated->errors()->keys(), __('messages.Data_not_valid'), 422);

        $validatedData = $validated->validated();

        if ($request->filled("coupon_code")) {
            $validator = Validator::make($request->all(), [
                'coupon_code' => 'required|exists:coupons,coupon_code',
            ]);
            if ($validator->fails())
                return $this->Response($validator->errors()->keys(), __('messages.Data_not_valid'), 422);
            $coupon = Coupon::where("coupon_code", $request->coupon_code)->first();
            if (Carbon::now()->gt(Carbon::parse($coupon->end_at))) {
                return $this->Response(null, 'Coupon has expired', 422);
            }
            $validatedData["coupon_id"] = $coupon->id;
        }


        $validatedData["required_gender"] = $request->gender;

        unset($validatedData['gender']);
        $validatedData["dropoff_lat"] = $request->type == 'trip' ? $request->dropoff_lat : null;
        $validatedData["dropoff_lng"] = $request->type == 'trip' ? $request->dropoff_lng : null;
        $validatedData["user_id"] = $request->user()->id;
        $validatedData["attachment"] = ($request->hasFile('attachment')) ? Helpers::upload_files($request->attachment, '/uploads/requests/') : null;
        $rideRequest = Requests::create($validatedData);

        $nearest_drivers = Helpers::get_nearest_drivers($request->pickup_lat, $request->pickup_lng);
        foreach ($nearest_drivers as $driver) {
            $channel = $request->type == 'trip' ?  "drivers" : "handymans";
            broadcast(new RequestEvent($rideRequest,$driver["id"], $channel))->toOthers();
        }

        return $this->Response($rideRequest, __("messages.Request_created_successfully"), 201);
    }

    public function accept_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:offers,id',
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), "Validation Error", 422);
        }
        $user = $request->user();
        $offer_id = $request->input("offer_id");
        try {
            list($offer, $ride_request) = Helpers::fetchNegotiationWithValidation($offer_id, 'user', $user);
            $final_price = $offer->proposed_price;
            $discount_value = 0;
            if ($ride_request->coupon) {
                $after_apply_coupon = Helpers::apply_coupon($ride_request->coupon, $offer->proposed_price);
                if (is_array($after_apply_coupon)) {
                    $final_price = $after_apply_coupon["final_price"];
                    $discount_value = $after_apply_coupon["discount_value"];
                    $coupon_id = $after_apply_coupon["coupon_id"];
                    $admin_profit = $after_apply_coupon["admin_profit"];
                } else {
                    return $after_apply_coupon;
                }
            } else {
                $admin_profit = adminProfit($final_price);
            }
            $ride_request->update([
                "provider_id" => $offer->provider_id,
                "accepted_price" => $final_price,
                "discount" => $discount_value,
                "coupon_id" => $coupon_id ?? null,
                "admin_profit" => $admin_profit ?? null,
                "status" => "accepted",

            ]);
            $ride_request->offers()->where("id", "!=", $offer->id)->update(["status" => "rejected",]);
            $offer->update(["status" => "accepted",]);
            broadcast(new OfferStatusEvent($offer));
            $data = [
                "user" => $offer->provider,
                "title" => "User Accepted Your Offer",
                "description" => "Your request has been accepted by " . $ride_request->user->name,
                "model_type" => "accept_offer",
                "model_id" => $ride_request->id
            ];
            $user = $data['user'];
            Helpers::push_notification($data);

            ChatRooms::firstOrCreate([
                "request_id" => $ride_request->id,
                "provider_id" => $offer->provider_id,
                "user_id" => $request->user()->id,
            ]);
            return $this->Response($offer, __("messages.The_offer_has_been_accepted_successfully"), 201);
        } catch (\Exception $e) {
            return $this->Response(null, $e->getMessage(), $e->getCode());
        }
    }

    public function rejected_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:offers,id',
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), "Validation Error", 422);
        }
        $user = $request->user();
        $offer_id = $request->input("offer_id");
        try {
            list($offer, $ride_request) = Helpers::fetchNegotiationWithValidation($offer_id, 'user', $user);
            $offer->update([
                "status" => "rejected"
            ]);
            broadcast(new OfferStatusEvent($offer));

            $data = [
                "user" => $offer->provider,
                "title" => "User Rejected Your Offer",
                "description" => "Your request has been rejected by " . $ride_request->user->name,
                "model_type" => "rejected_offer",
                "model_id" => $ride_request->id
            ];

            Helpers::push_notification($data);



            return $this->Response($offer, __("messages.The_offer_has_been_rejected_successfully1"), 201);
        } catch (\Exception $e) {
            return $this->Response(null, $e->getMessage(), $e->getCode());
        }
    }



    public function nearest_drivers(Request $request)
    {
        $validator = validator($request->all(), [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $userLat = $request->lat;
        $userLng = $request->lng;

        $finalDrivers = Helpers::get_nearest_drivers($userLat, $userLng);

        $data = [
            "drivers" => $finalDrivers
        ];
        return $this->Response($data, "Drivers", 201);
    }
}
