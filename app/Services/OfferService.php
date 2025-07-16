<?php

namespace App\Services;

use App\Models\Offers;
use App\Models\ChatRooms;
use App\Events\OfferStatusEvent;
use App\Services\NotificationService;
use App\Exceptions\CustomValidationException;

class OfferService
{
    private $CouponService;
    private $NotificationService;
    function __construct(CouponService $CouponService, NotificationService $NotificationService)
    {
        $this->CouponService = $CouponService;
        $this->NotificationService = $NotificationService;
    }
    function get_user_offers($request_id)
    {
        $offers = Offers::with('provider', 'request.service')->orderBy('id', 'desc')->where("request_id", $request_id)->get()->groupBy('provider_id');
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
        return $result;
    }
    public  function fetchNegotiationWithValidation($offerId, $role, $user)
    {
        $offer = Offers::with(['ride_request', 'provider'])->findOrFail($offerId);
        $rideRequest = $offer->ride_request;
        if ($rideRequest->status !== 'pending') {
            throw new CustomValidationException('Request not available', 403);
        }
        if ($role === 'driver') {
        } elseif ($role === 'user') {
            if ($rideRequest->user_id !== $user->id) {
                throw new CustomValidationException('Not allowed', 403);
            }
        } else {
            throw new CustomValidationException('Invalid role', 400);
        }

        return [$offer, $rideRequest];
    }

    public function accept_offer($offer_id, $account)
    {
        list($offer, $ride_request) = $this->fetchNegotiationWithValidation($offer_id, 'user', $account);
        $final_price = $offer->proposed_price;
        $discount_value = 0;
        if ($ride_request->coupon) {
            $after_apply_coupon = $this->CouponService->apply_coupon($ride_request->coupon, $offer->proposed_price);
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
        $this->NotificationService->push_notification($data);

        ChatRooms::firstOrCreate([
            "request_id" => $ride_request->id,
            "provider_id" => $offer->provider_id,
            "user_id" => $account->id,
        ]);
        return $offer;
    }

    public function rejected_offer($offer_id, $user)
    {
        list($offer, $ride_request) = $this->fetchNegotiationWithValidation($offer_id, 'user', $user);
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

        $this->NotificationService->push_notification($data);
        return $offer;
    }
}
