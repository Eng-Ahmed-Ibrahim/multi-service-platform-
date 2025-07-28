<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Services\LocationService;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

class LocationsController extends Controller
{
    use ResponseTrait;
    private $LocationService;
    function __construct(LocationService $LocationService)
    {
        $this->LocationService = $LocationService;
    }
    public function index(Request $request)
    {
        $locations = Location::where("user_id", $request->user()->id)
            ->orderBy("id", "DESC")
            ->get();
        return $this->Response(LocationResource::collection($locations), "Locations", 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "latitude" => "required",
            "longitude" => "required",
            "title"=>"required",
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), "Validation Error", 422);
        }
        $lat = $request->latitude;
        $lng = $request->longitude;
        $location = Location::create([
            "user_id" => $request->user()->id,
            "latitude" => $lat,
            "longitude" => $lng,
            'name_en' => $this->LocationService->get_location_name($lat, $lng, "en") ?? 'Unknown',
            'name_ar' => $this->LocationService->get_location_name($lat, $lng, "ar") ?? 'غير معروف',
            "title"=>$request->title,
        ]);
        return $this->Response(new LocationResource($location), __("messages.Location Added Successfully"), 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "latitude" => "required",
            "longitude" => "required",
            "title"=>"required",

        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }
        $lat = $request->latitude;
        $lng = $request->longitude;
        $user = $request->user();
        $location =Location::where('id', $id)->where('user_id', $user->id)->first();
        if (! $location)
            return $this->Response(null, " Not Found", 404);

        if ($location->latitude != $lat || $location->longitude != $lng) {
            $location->latitude=$lat;
            $location->longitude=$lng;
            
            $location->name_en=$this->LocationService->get_location_name($lat, $lng, "en") ?? 'Unknown';
            $location->name_ar=$this->LocationService->get_location_name($lat, $lng, "ar") ?? 'غير معروف';
            


        }
        $location->title=$request->title;
        $location->save();
        return $this->Response(new LocationResource($location), __("messages.Location Updated Successfully"), 200);
        
    }
    public function destroy(Request $request , $id)
    {
        $user = $request->user();
        $location =Location::where('id', $id)->where('user_id', $user->id)->first();
        if (! $location)
            return $this->Response(null, " Not Found", 404);

        $location->delete();
        return $this->Response(new LocationResource($location), __("messages.Location Deleted Successfully"), 200);

    }
}
