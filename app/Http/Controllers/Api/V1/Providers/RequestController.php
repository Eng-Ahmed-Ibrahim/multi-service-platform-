<?php

namespace App\Http\Controllers\Api\V1\Providers;

use Carbon\Carbon;
use App\Models\Offers;
use App\Helpers\Helpers;
use App\Models\Requests;
use Illuminate\Http\Request;
use App\Events\CreateOfferEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;
use App\Events\NewOffersToSpecificRequestEvent;

class RequestController extends Controller
{
    use ResponseTrait;
    public function requests(Request $request)
    {
        $scheduleRequest=Helpers::getScheduledRequest($request->user() );
        if(! empty($scheduleRequest)){
            $data=[
                "is_scheduled"=>1,
                "requests"=>array($scheduleRequest),
            ];
            return $this->Response($data, __("messages.You Have A Schedule Request , Be Ready"), 201);
        }
        // $query = Requests::query();
        // if ($request->user()->role == "driver")
        //     $query->where("type", "trip");
        // else 
        //     $query->whereIn('type',["car_service","home_service"]);
        // $UsersRequests = $query->with(["user"])
        //     ->whereIn("status", ["pending","accepted"])
        //     ->where(function ($q) use ($request) {
        //         $q->whereNull("required_gender")
        //             ->orWhere("required_gender", $request->user()->gender);
        //     })
        //     ->with(['service:id,name,name_ar,image'])
        //     ->orderBy("id", 'DESC')
        //     ->get();
        $driver=$request->user();
        $nearest_request= Helpers::get_nearest_requests($driver->lat,$driver->lng,$driver);
        $data=[
            "is_scheduled"=>0,
            "requests"=>$nearest_request,
        ];
        return $this->Response($data, __('messages.Requests'), 201);
    }
    public function request_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required|exists:requests,id",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), __("messages.Validation Error"), 422);
        $offers = Offers::where("provider_id", $request->user()->id)
            ->where("request_id", $request->request_id)
            ->with(["provider"])
            ->get();
        return $this->Response($offers, __('messages.offers'), 201);
    }
    public function create_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|exists:requests,id',
            'proposed_price'  => 'required|numeric|min:0',
            'message'         => 'nullable|string|max:255',
            'date'         => 'nullable|string|max:255',
            'time'         => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }
        $ride_request = Requests::find($request->request_id);
        if ($ride_request->status !== 'pending') {
            return $this->Response(null, "Request not available", 422);
        }
        if (get_class($request->user()) == "App\Models\User")
            return $this->Response(null, "Can't Create Offer", 422);

        if ($ride_request->type == "trip" && $request->user()->role != "driver" || $ride_request->type == "maintenance" && $request->user()->role != "handyman")
            return $this->Response(null, __("messages.Not_allowed"), 403);

        $has_offer = Offers::where("request_id", $request->request_id)->where("status", "!=", 'cancelled')->where("status", '!=', 'rejected')->where("provider_id", $request->user()->id)->exists();
        if ($has_offer)
            return $this->Response(null, "You already have an offer", 422);


        $validated = $validator->validated();
        $validated["status"] = "pending";
        $validated['provider_id'] = $request->user()->id;
        $offer = Offers::create($validated);
        $provider = $request->user();
        broadcast(new CreateOfferEvent($offer));
        broadcast(new NewOffersToSpecificRequestEvent($offer, $ride_request->user_id, $ride_request->id, $ride_request->service_id, $provider));

        $data = [
            "user" => $ride_request->user,
            "title" => "New Offer",
            "description" => "You have a new offer from " . $provider->name,
            "model_type" => "create_offer",
            "model_id" => $offer->request_id,
        ];
        Helpers::push_notification($data);


        return $this->Response($offer, __('messages.Offer_sent_successfully'), 201);
    }
    public function cancel_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "offer_id" => "required|exists:offers,id",
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }

        $offer = Offers::find($request->offer_id);
        $user = $offer->ride_request->user;
        $provider = $offer->provider;
        $offer->update([
            "status" => "cancelled",
        ]);

        $data = [
            "user" => $user,
            "title" => "$provider->name  has Canceled The Offer",
            "description" => "You have a new offer from " . $provider->name,
            "model_type" => "cancel_offer",
            "model_id" =>  $offer->request_id
        ];
        Helpers::push_notification($data);

        return $this->Response(null, "Offer cancelled successfully", 201);
    }
    public function start_trip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required|exists:requests,id",
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }
        $cur_request = Requests::find($request->request_id);
        if ($cur_request->provider_id != $request->user()->id)
            return $this->Response(null, "You are not the provider of this request", 422);
        if ($cur_request->status != "accepted")
            return $this->Response(null, "Request Not Accepted Yet !", 422);
        $cur_request->update([
            "status" => "started",
        ]);
        $data = [
            "user" => $cur_request->user,
            "title" => "Request Started",
            "description" => "Your request has been started by " . $cur_request->provider->name,
            "model_type" => "start_trip",
            "model_id" => $cur_request->id
        ];
        Helpers::push_notification($data);


        return $this->Response(null, "Trip started successfully", 201);
    }
    public function complete_trip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required|exists:requests,id",
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }
        $cur_request = Requests::find($request->request_id);
        if ($cur_request->provider_id != $request->user()->id)
            return $this->Response(null, "You are not the provider of this request", 422);
        if ($cur_request->status != "started")
            return $this->Response(null, "Request Not Started Yet !", 422);
        $cur_request->update([
            "status" => "completed",
        ]);
        $commission = getBusinessSetting("profit_percentage");
        $price_before_discount = $cur_request->accepted_price  + $cur_request->discount;
        $amount = ($price_before_discount * $commission / 100) - $cur_request->discount;
        reduceBalance($cur_request->provider, $amount, "Trip Commission");

        $data = [
            "user" => $cur_request->user,
            "title" => "Request Completed",
            "description" => "Your request has been completed by " . $cur_request->provider->name,
            "model_type" => "complete_trip",
            "model_id" => $cur_request->id
        ];
        Helpers::push_notification($data);

        return $this->Response(null, "Trip completed successfully", 201);
    }
}
