<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Requests;
use App\Models\Notification;
use Illuminate\Console\Command;

class TestCron extends Command
{

    protected $signature = 'test:cron';
    protected $description = 'Command description';

    public function handle()
    {
        $now = Carbon::now('Africa/Cairo');
        $today = $now->format('m/d/Y');
    
        $scheduledRequests = Requests::where('date', $today)
            ->where("is_reminded", 0)
            ->where("provider_id", "!=", null)
            ->with(['user', 'service'])
            ->get();
    
        foreach ($scheduledRequests as $scheduledRequest) {
            $diffInMinutes = $now->diffInMinutes($scheduledRequest->time, false);
            if ($diffInMinutes > 0 && $diffInMinutes <= 60) {
                Helpers::push_notification([
                    "user" => $scheduledRequest->provider,
                    "title" => "Reminder",
                    "description" => "You have a scheduled request in less than 1 hour",
                    "model_type" => "reminder_scheduled_request",
                    "model_id" =>  $scheduledRequest->id,
                ]);
                Helpers::push_notification([
                    "user" => $scheduledRequest->user,
                    "title" => "Reminder",
                    "description" => "You have a scheduled request in less than 1 hour",
                    "model_type" => "reminder_scheduled_request",
                    "model_id" => $scheduledRequest->id,
                ]);
                $scheduledRequest->update(['is_reminded' => 1]);
                Notification::create([
                    "subject" => "0Test Notification",
                    "message" => "0This is a Reminder notification",
                    "to"=>"all",
                ]);
            }
        }
    }
}
