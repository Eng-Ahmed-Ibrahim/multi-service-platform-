<?php 
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TestReverbEvent implements ShouldBroadcastNow
{
    
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('test-channel'); // Public channel for testing
    }

public function broadcastAs()
{
    return 'message-sent'; // Change the event name here
}

    public function broadcastWith()
    {
        return [
            'message' => $this->message, // Send the actual message passed during event instantiation
        ];
    }
}
