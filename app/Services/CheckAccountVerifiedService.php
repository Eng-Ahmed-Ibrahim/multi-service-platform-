<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class CheckAccountVerifiedService
{
    public static function checkVerificationStatus($user,$token=null,$status=403)
    {
        if ($user->phone_verified == 0) {
            return response()->json([
                "message" => "Please verify your phone number",
                "token" => $token
            ], $status);
        }

        if ($user->is_verified != 1 && $user->role != 'user') {
            $processes = DB::table('processes')->where('provider_id', $user->id)->get();

            $accepted = $processes->where('status', 'accepted')->pluck('process_number')->toArray();
            $rejected = $processes->where('status', 'rejected')->map(function ($process) {
                return [
                    'step' => $process->process_number,
                    'reason' => $process->rejection_reason
                ];
            })->values()->toArray();
            $pending = $processes->where('status', 'pending')->pluck('process_number')->toArray();

            return response()->json([
                "message" => "Please verify your account",
                "status" => "pending",
                "accepted" => $accepted,
                "rejected" => $rejected,
                "pending" => $pending,
                "token" => $token
            ], $status);
        }

        return null;
    }
}
