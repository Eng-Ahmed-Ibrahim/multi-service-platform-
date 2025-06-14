<?php
namespace App\Services;

use App\Models\FcmTokens;
use Illuminate\Support\Facades\DB;

class AddNewFcmService
{
    public static function addFcm($user,$fcm,$device_id)
    {

        $fcmToken=FcmTokens::where('token',$fcm)
        ->where("account_id",$user->id)
        ->where("account_type",get_class($user))
        ->first();
        if($fcmToken){
            return false;
        }
        FcmTokens::create([
            "account_id"=>$user->id,
            "account_type"=>get_class($user),
            "token"=>$fcm,
            "device_id"=>$device_id,
        ]);
        return true;
    }
}
