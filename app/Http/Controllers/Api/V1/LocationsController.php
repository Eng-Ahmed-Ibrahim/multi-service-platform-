<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helpers;
use App\Models\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\BroadcastNearestRequest;
use App\Events\PushDriverToNearestLocatioo;
use App\Http\Controllers\Api\ResponseTrait;

class LocationsController extends Controller
{
    use ResponseTrait;
    public function update_location(Request $request){
        $valodator = validator($request->all(),[
            "lat"=>"required|numeric",
            "lng"=>"required|numeric",
        ]);
        if($valodator->fails()){
            return $this->Response([],$valodator->errors()->first(),422);
        }
        $provider = $request->user();   
        $provider->lat = $request->lat;
        $provider->lng = $request->lng; 
        $provider->last_seen = now(); 
        $provider->save();
        $currect_provider_request= Requests::where("provider_id",$provider->id)->where("status",'accepted')
        ->first();
        if($currect_provider_request){
            BroadcastNearestRequest::dispatch($provider, $currect_provider_request->user_id);
        }else{
            
            $nearest_requests= Helpers::get_nearest_requests($provider->lat,$provider->lng);
            if(is_array($nearest_requests) && count($nearest_requests) > 0)
                foreach($nearest_requests as $nearest_request){
                    BroadcastNearestRequest::dispatch($provider, $nearest_request['user_id']);
                    
                }
        }
        // return $this->Response($nearest_requests,'Nearest Requests',200);
        $data=[
            "lat"=>$provider->lat,
            "lng"=>$provider->lng,
        ];
        return $this->Response($data,'Location Updated Successfully',200);
    }
}
