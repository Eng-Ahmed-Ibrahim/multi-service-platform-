<?php

namespace App\Http\Controllers\Api\V1\Providers;

use App\Models\Brands;
use App\Models\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseTrait;
use App\Models\Models;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use ResponseTrait;
    public function services(Request $request){
        $validator=Validator::make(request()->all(),[
            "type" => "required|in:car_services,home_services,car_transportations",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Validation Error",422);
        $services=Services::where("status",true)
        ->where("section",$request->type)
        ->orderBy("id","DESC")->get();
        return $this->Response($services,"Services",200);
    }
    public function car_type(){
        $services=Services::where("status",true)->where("section",'car_transportations')->orderBy("id","DESC")->get();
        return $this->Response($services,"Car Type",200);
    }
    public function brands(Request $request){
        $brands=Brands::where("status",true)->where("service_id",$request->type_id)->orderBy("id","DESC")->get();    
        return $this->Response($brands,"Brands",200);
    }
    public function models(Request $request){
        $models=Models::where("status",true)->where("brand_id",$request->brand_id)->orderBy("id","DESC")->get();
        return $this->Response($models,"Models",200);
    }
}
