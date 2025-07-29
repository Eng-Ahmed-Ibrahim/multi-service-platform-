<?php

namespace App\Events;

use App\Models\Requests;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $channel;
    public $provider_id;
    public function __construct(Requests $request,$provider_id,$channel)
    {
        $this->request = $request->load(['user', 'service']); 
        $this->channel = $channel;
        $this->provider_id = $provider_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("requests.{$this->channel}.{$this->provider_id}");

    }

    public function broadcastAs()
    {
        return 'requests';
    }

    public function broadcastWith()
    {
        return [
            "id" => $this->request->id,
            "pickup_lat" => $this->request->pickup_lat,
            "pickup_lng" => $this->request->pickup_lng,
            "dropoff_lat" => $this->request->dropoff_lat,
            "dropoff_lng" => $this->request->dropoff_lng,
            "current_price" => $this->request->current_price,
            "type" => $this->request->type,
            "description" => $this->request->description,
            "date" => $this->request->date,
            "time" => $this->request->time,
            "attachment" => $this->request->attachment,
            "user" => $this->request->user,
            "service" => $this->request->service,
            "created_at" => $this->request->created_at,
        ];
    }
}
