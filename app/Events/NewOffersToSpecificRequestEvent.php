<?php

namespace App\Events;

use App\Models\Offers;
use App\Helpers\Helpers;
use App\Models\Services;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewOffersToSpecificRequestEvent  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offer;
    public $user_id;
    public $request_id;
    public $service_id;
    public $provider;

    public function __construct(Offers $offer,$user_id,$request_id,$service_id,$provider)
    {
        $this->offer = $offer;
        $this->request_id= $request_id;
        $this->user_id= $user_id;
        $this->service_id= $service_id;
        $this->provider= $provider;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel("new_offers.{$this->user_id}.{$this->request_id}"),
            new PrivateChannel("push-driver-to-map.{$this->user_id}"),
        ];
    }
    

    public function broadcastAs()
    {
        return 'new_offers_of_specific_request';
    }
// 
    public function broadcastWith()
    {
        $service=Services::find($this->service_id);
        $this->provider['rating']=Helpers::provider_rating($this->offer->provider_id);
        return [
            "type"=>"new_offers_of_specific_request",
            "id"=>$this->offer->id,
            "message" => $this->offer->message, 
            "date" => $this->offer->date, 
            "time" => $this->offer->time,
            "request_id" => $this->offer->request_id,
            "provider"=>$this->provider,
            "service"=>[
                "id"=>$service->id,
                "name"=>$service->name,
                "name_ar"=>$service->name_ar,
                "image"=>$service->image,
            ],
            "last_offer_price" => $this->offer->proposed_price, 
            

        ];
    }
}
