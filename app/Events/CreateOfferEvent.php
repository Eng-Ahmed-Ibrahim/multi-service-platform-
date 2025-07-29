<?php

namespace App\Events;

use App\Models\Offers;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateOfferEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offer;

    public function __construct(Offers $offer)
    {
        $this->offer = $offer;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("new_offers.{$this->offer->request->user_id}");
    }

    public function broadcastAs()
    {
        return 'new_offers';
    }

    public function broadcastWith()
    {
        return [
            "id"=>$this->offer->id,
            "proposed_price" => $this->offer->proposed_price, 
            "message" => $this->offer->message, 
            "date" => $this->offer->date, 
            "time" => $this->offer->time,
            "request_id" => $this->offer->request_id, 
        ];
    }
}
