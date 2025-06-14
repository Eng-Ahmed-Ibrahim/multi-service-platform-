<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Providers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseTrait;
use App\Models\User;
use App\Services\CheckAccountVerifiedService;

class SwitchAccountController extends Controller
{
    use ResponseTrait;
    public function switch_account(Request $request){
        $account=$request->user();
        $type = get_class($account);
        if ($type == "App\Models\User" && $account->provider_id != null) {
            $account->current_loggin=false;
            $account->save();


            $user = Providers::find($account->provider_id);
            if (!$user) {
                return $this->Response(null, "User account not found", 404);
            }
            $request->user()->currentAccessToken()->delete();
            $user->update([
                'current_loggin'=>true,
            ]);
            $token = $user->createToken('Driver Token')->plainTextToken;
            $data = [
                "user" => $user,
                "token" => $token,
            ];
            $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user,$token,403);
            if ($verificationResponse) {
                return $verificationResponse;
                
            }
            
            return $this->Response($data, "Switched Successfully", 200);
        }elseif($type == "App\Models\Providers"){
            if($account->user_id != null){
                $account->current_loggin=false;
                $account->save();
                $request->user()->currentAccessToken()->delete();

                $user = User::find($account->user_id);

                $token = $user->createToken('Driver Token')->plainTextToken;
                $data = [
                    "user" => $user,
                    "token" => $token,
                ];
                $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user,$token,403);
                if ($verificationResponse) {
                    return $verificationResponse;
                }
                
                return $this->Response($data, "Switched Successfully", 200);
            }else{
                
                $user = User::create([
                    "name" => $account->name,
                    "phone" => $account->phone,
                    "date_of_birth" => $account->date_of_birth,
                    "address" => $account->address,
                    "password" => $account->password,
                    "image" => "images/default/avatar.png",
                    "email" => $account->email,
                    "current_loggin"=>true,
                    "phone_verified"=>1,
                    "is_phone_verified"=>1,
                    "provider_id"=>$account->id,
                ]);
                $account->current_loggin=false;
                $account->user_id=$user->id;
                $account->save();
                $request->user()->currentAccessToken()->delete();
        
                $token = $user->createToken('User Token', ['*'], now()->addWeek())->plainTextToken;
        
                $data = [
                    'user' => $user,
                    "token" => $token,
                ];
        
                // $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user,$token,200);
                // if ($verificationResponse) {
                //     return $verificationResponse;
                // }
                return $this->Response($data, "Switched Successfully", 201);


            }
        }
        return $this->Response(null,"Something Wrong , try latter ",422);
    }
}
