<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Models\Reviews;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;
class ReviewsController extends Controller
{
    use ResponseTrait;

    public function add_review(Request $request){
        $validator=Validator::make($request->all(),[
            "provider_id"=>"required",
            'rating' => 'required|numeric|between:1,5',
        ]);
        if($validator->fails()){
            return $this->Response($validator->errors()->first(),__("messages.Validation Error"),422);
        }
        Reviews::create([
            "user_id"=>auth()->user()->id,
            "provider_id"=>$request->provider_id,
            "rating"=>$request->rating,
            "message"=>$request->message,
        ]);
        return $this->Response(__("messages.Review Added Successfully"),__("messages.Review Added Successfully"),200);



 
    }
}
