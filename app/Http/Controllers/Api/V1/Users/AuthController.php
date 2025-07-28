<?php
namespace App\Http\Controllers\Api\V1\Users;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\AddNewFcmService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;
use App\Services\CheckAccountVerifiedService;

class AuthController extends Controller
{
    use ResponseTrait;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "phone" => "required",
            'password' => 'required', 
            "fcm_token" => "required",
            "device_id" => "required",
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }
        $phone = $request->phone;

        if (!Str::startsWith($phone, '+')) {
            $phone = '+' . $phone;
        }
        $user = User::where("phone", $phone)->first();

        if ( ! $user || ! Hash::check($request->password, $user->password)) {
            return $this->Response(__("messages.Incorrect phone or password"), __("messages.Incorrect phone or password"), 401);
        }
        AddNewFcmService::addFcm($user,$request->fcm_token,$request->device_id);

        $token = $user->createToken('User Token')->plainTextToken;
        $data = [
            "user" => $user,
            "token" => $token,
        ];
        $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user,$token,403);
        if ($verificationResponse) {
            return $verificationResponse;
        }

        return $this->Response($data, __("messages.Login Successfully"), 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "phone" => "required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/",
            'password' => 'required|min:8',
            "email" => "required|email|unique:users",
            "address"=>"required",
            "date_of_birth"=>"required",
            "fcm_token" => "required",
            "device_id" => "required",
        ], [
            'name.required' => __('messages.required', ['attribute' => __('messages.attributes.name')]),
            'phone.required' => __('messages.required', ['attribute' => __('messages.attributes.phone')]),
            'phone.unique' => __('messages.phone_already_exists'),
            'email.required' => __('messages.required', ['attribute' => __('messages.attributes.email')]),
            'email.unique' => __('messages.email_already_exists'),
            'password.required' => __('messages.required', ['attribute' => __('messages.attributes.password')]),
            'phone.regex' => __('messages.Phone_number_not_valid'),
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors(), __("messages.Validation Error"), 422);
        }
        $dateOfBirth = Carbon::createFromFormat('m/d/Y', $request->date_of_birth)->format('Y-m-d');

        $user = User::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "date_of_birth" => $dateOfBirth,
            "address" => $request->address,
            "password" => bcrypt($request->password),
            "image" => $request->image ?? "images/default/avatar.png",
            "email" => $request->email,
        ]);        
        AddNewFcmService::addFcm($user,$request->fcm_token,$request->device_id);


        $token = $user->createToken('User Token', ['*'])->plainTextToken;

        $data = [
            'user' => $user,
            "token" => $token,
        ];

        $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user,$token,200);
        if ($verificationResponse) {
            return $verificationResponse;
        }
        return $this->Response($data, __("messages.Registered Successfully"), 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->Response(null, __("messages.Logged Out Successfully"), 200);
    }
}
