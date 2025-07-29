<?php

namespace App\Events;

use App\Models\Offers;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OfferStatusEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offer;

    public function __construct(Offers $offer)
    {
        $this->offer = $offer;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("offers-status.{$this->offer->provider_id}.{$this->offer->request_id}");
    }

    public function broadcastAs()
    {
        return 'offers-status';
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
            "status"=>$this->offer->status,
        ];
    }
}
