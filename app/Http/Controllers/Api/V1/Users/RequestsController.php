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
use App\Services\OfferService;
use App\Services\CouponService;
use App\Events\OfferStatusEvent;
use App\Services\RequestService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

class RequestsController extends Controller
{
    use ResponseTrait;
    private $requestService;
    private $OfferService;
    private $CouponService;
    function __construct(RequestService $requestService ,
     OfferService $OfferService, CouponService $CouponService)
    {
        $this->requestService = $requestService;
        $this->OfferService = $OfferService;
        $this->CouponService = $CouponService;
    }
    public function requests(Request $request)
    {
        $user=$request->user();
        $scheduleRequest = $this->requestService->getScheduledRequest($user);
        if (! empty($scheduleRequest)) {
            $data = [
                "is_scheduled" => 1,
                "requests" => array($scheduleRequest),
            ];
            return $this->Response($data, __("messages.You Have A Schedule Request , Be Ready"), 201);
        }
        $UsersRequests = $this->requestService->get_user_request($user);
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
            return $this->Response($validator->errors(), __("messages.Validation Error"), 422);
        
        $result = $this->OfferService->get_user_offers($request->request_id);
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
        $rideRequest = $this->requestService->add_request($validatedData, $request);
        return $this->Response($rideRequest, __("messages.Request_created_successfully"), 201);
    }

    public function accept_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:offers,id',
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }
        $user = $request->user();
        $offer_id = $request->input("offer_id");
        $offer = $this->OfferService->accept_offer($offer_id , $user);
        return $this->Response($offer , __("messages.Accepted Offer Successfully"), 201);
    }

    public function rejected_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:offers,id',
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }
        $user = $request->user();
        $offer_id = $request->input("offer_id");
        $offer = $this->OfferService->rejected_offer($offer_id , $user);
        return $this->Response($offer, __("messages.The_offer_has_been_rejected_successfully"), 201);
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
        return $this->Response($data, __("messages.Drivers"), 201);
    }
}
