<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseTrait;

class SettingsController extends Controller
{
    use ResponseTrait;
    public function privacy_policy(Request $request){
        $user=$request->user();
        $keys = [];

        if ($user->role == "user") {
            $keys = ["privacy_users_en", "privacy_users_ar", "policy_users_en", "policy_users_ar"];
        } elseif ($user->role == "driver") {
            $keys = ["privacy_drivers_en", "privacy_drivers_ar", "policy_drivers_en", "policy_drivers_ar"];
        } elseif ($user->role == "handyman") {
            $keys = ["privacy_handymans_en", "privacy_handymans_ar", "policy_handymans_en", "policy_handymans_ar"];
        }
        $settings = BusinessSetting::whereIn("key", $keys)->pluck("value", "key");
        $data = [
            "privacy_en" => $settings[$keys[0]] ?? null,
            "privacy_ar" => $settings[$keys[1]] ?? null,
            "policy_en" => $settings[$keys[2]] ?? null,
            "policy_ar" => $settings[$keys[3]] ?? null,
        ];
        
        return $this->Response($data, __("messages.Privacy and Policy Fetched Successfully"), 200);
        
    }
    public function terms(Request $request){
        $user=$request->user();
        $keys = [];

        if ($user->role == "user") {
            $keys = ["terms_users_en", "terms_users_ar"];
        } elseif ($user->role == "driver") {
            $keys = ["terms_drivers_en", "terms_drivers_ar"];
        } elseif ($user->role == "handyman") {
            $keys = ["terms_handymans_en", "terms_handymans_ar"];
        }
        $settings = BusinessSetting::whereIn("key", $keys)->pluck("value", "key");
        $data = [
            "terms_en" => $settings[$keys[0]] ?? null,
            "terms_ar" => $settings[$keys[1]] ?? null,

        ];
        
        return $this->Response($data, __("messages.Terms and Condition Fetched Successfully"), 200);
        
    }
}
