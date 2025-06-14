<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PushDriverToNearestLocatioo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BroadcastNearestRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $provider;
    public $userId;

    public function __construct($provider, $userId)
    {
        $this->provider = $provider;
        $this->userId = $userId;
    }

    public function handle()
    {
        broadcast(new PushDriverToNearestLocatioo($this->provider, $this->userId))->toOthers();
    }
}
