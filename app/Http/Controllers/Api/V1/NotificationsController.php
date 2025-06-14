<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseTrait;

class NotificationsController extends Controller
{
    use ResponseTrait;

    public function get_notifications(Request $request){
        $notifications=Notification::where("to","all")->orWhere("to",$request->user()->role)
        ->orderBy("id","DESC")
        ->select("id",'subject','subject_ar','message','message_ar',"created_at")
        ->get();
        $data=[
            
            "notifications"=>$notifications,
        ];
        return $this->Response($data,' ',200);
    }
}
