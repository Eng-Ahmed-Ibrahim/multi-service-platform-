<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

class ProfileController extends Controller
{
    use ResponseTrait;

    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            "status" => 200,
            "message" => "Profile",
            "data" => $user
        ]);
    }
    public function update_profile(Request $request)
    {
        $vaidator = Validator::make($request->all(), [
            "f_name" => "required|string",
            "l_name" => "required|string",
            "email" => "required|email",
            "address" => "required|string",
            "image" => "nullable|image",
        ]);
        if ($vaidator->fails())
            return $this->Response($vaidator->errors(), "Validation Error", 422);
        $user = $request->user();
        if ($request->hasFile("image")) {
            Helpers::delete_file($user->image);
            $user->image = Helpers::upload_files($request->image, "/uploads/drivers/account_photos/");
        }
        $user->name = $request->f_name . " " . $request->l_name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->save();
        return response()->json([
            "status" => 200,
            "message" => "Profile Updated",
            "data" => $user
        ]);
    }
    public function update_image(Request $request)
    {
        $vaidator = Validator::make($request->all(), [
            "image" => "required|image",
        ]);
        if ($vaidator->fails())
            return $this->Response($vaidator->errors(), "Validation Error", 422);
        $user = $request->user();
        Helpers::delete_file($user->image);
        $user->image = Helpers::upload_files($request->image, "/uploads/drivers/account_photos/");
        $user->save();
        return response()->json([
            "status" => 200,
            "message" => "Profile Image Updated",
            "data" => $user
        ]);
    }
    public function change_password(Request $request)
    {
        $vaidator = Validator::make($request->all(), [
            "old_password" => "required|string",
            "new_password" => "required|string",
            "confirm_password" => "required|string|same:new_password",
        ]);
        if ($vaidator->fails())
            return $this->Response($vaidator->errors(), "Validation Error", 422);
        $user = $request->user();
        if (! Hash::check($request->old_password, $user->password))
            return $this->Response("Old Password is incorrect", "Validation Error", 422);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return $this->Response("Password Changed Successfully", "Success", 200);
    }
}
