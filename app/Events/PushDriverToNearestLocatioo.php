<?php

namespace App\Events;

use App\Models\User;
use App\Models\Providers;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PushDriverToNearestLocatioo  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $driver;
    public $user_id;

    public function __construct(Providers $driver,$user_id)
    {
        $this->driver = $driver;
        $this->user_id= $user_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("push-driver-to-map.{$this->user_id}");
    }

    public function broadcastAs()
    {
        return 'pushـdriverـtoـmap';
    }

    public function broadcastWith()
    {
        return [
            "type"=>"pushـdriverـtoـmap",
            "id"=>$this->driver->id,
            "name" => $this->driver->name, 
            "lat" => $this->driver->lat, 
            "lng" => $this->driver->lng, 
        ];
    }
}
