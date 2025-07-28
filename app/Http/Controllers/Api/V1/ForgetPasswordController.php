<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Requests;
use App\Models\Providers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

class ForgetPasswordController extends Controller
{
    use ResponseTrait;
    public function send_otp(Request $request){
            $validator = Validator::make($request->all(), [
                "phone" => "required",
            ]);
        
            if ($validator->fails()) {
                return $this->Response($validator->errors(), __("messages.Validation Error"), 422);
            }
        
            $user = User::where('phone', $request->phone)->first();
            $provider = Providers::where('phone', $request->phone)->first();
        
            if ($user) {
                $data = [
                    "otp" => 00000,
                ];
                return $this->Response($data, __("messages.Otp sent"), 200);
            } elseif ($provider) {
                $data = [
                    "otp" => 00000,
                ];
                return $this->Response($data, __("messages.Otp sent"), 200);
            }
        
            return $this->Response([], __("messages.Otp sent"), 404);
    }
    public function verify_otp(Request $request){
        $validator = Validator::make($request->all(), [
            "phone" => "required",
            "otp" => "required",
        ]);
    
        if ($validator->fails()) {
            return $this->  Response($validator->errors(), __("messages.Validation Error"), 422);
        }
    
        $user = User::where('phone', $request->phone)->first();
        $provider = Providers::where('phone', $request->phone)->first();
    
        if ($user) {
            if ($request->otp == 00000) {
                $token = $user->createToken('Driver Token')->plainTextToken;

                $data = [
                    "user" => $user,
                    "token" => $token,
                ];
        
                return $this->Response($data, __("messages.Otp verified"), 200);
            }
            return $this->Response([], __("messages.Invalid OTP"), 422);
        } elseif ($provider) {
            if ($request->otp == 00000) {
                $token = $provider->createToken('Driver Token')->plainTextToken;

                $data = [
                    "user" => $provider,
                    "token" => $token,
                ];
        
                return $this->Response($data, __("messages.Otp verified"), 200);
            }
            return $this->Response([], __("messages.Invalid OTP"), 422);
        }
    
        return $this->Response([], __("messages.Phone number not found"), 404);
    }
    public function update_password(Request $request){
        $validator = Validator::make($request->all(), [
            "new_password" => "required|string",
            "confirm_password" => "required|string|same:new_password",
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors(), __("messages.Validation Error"), 422);
        }
        $user = request()->user();
        $user->password=Hash::make($request->new_password);
        $user->save();
        return $this->Response([], __("messages.Phone number not found"), 404);
    }
}
