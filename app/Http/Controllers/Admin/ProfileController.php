<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Helpers\Helpers;
use App\Models\Providers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    
    public function index($id, Request $request)
    {
        // Get the model type from the request (e.g., 'user', 'admin', 'provider')
        $modelType = $request->input('model');
    
        // Map the model types to actual classes
        $models = [
            'user' => User::class,
            'admin' => Admin::class,
            'provider' => Providers::class,
        ];
    
        // Check if the provided model type is valid
        if (!array_key_exists($modelType, $models)) {
            session()->flash('error', __('Invalid model type'));
            return back();
        }
    
        // Fetch the user using the specified model
        $modelClass = $models[$modelType];
        $profile = $modelClass::find($id);
    
        // If the user doesn't exist, return an error
        if (!$profile) {
            session()->flash('error', __('Record not found'));
            return back();
        }
    
        // Fetch roles or other necessary data
        $roles = Role::all();
    
        return view('admin.profile.index', compact('profile', 'roles', 'modelType'));
    }
    

    public function update_profile($profile_id, Request $request)
    {
        $modelType = $request->input('model');
        // Map the model types to actual classes
        $models = [
            'user' => User::class,
            'admin' => Admin::class,
            'provider' => Providers::class,
        ];
    
        // Check if the provided model type is valid
        if (!array_key_exists($modelType, $models)) {
            session()->flash('error', __('Invalid model type'));
            return back();
        }
    
        // Fetch the user using the specified model
        $modelClass = $models[$modelType];
        $profile = $modelClass::find($profile_id);
    
        // If the user doesn't exist, return an error
        if (!$profile) {
            session()->flash('error', __('Record not found'));
            return back();
        }
        $request->validate([
            "name" => "required",
            "email" => "required",
        ]);
        if ($request->hasFile('image')) {
            Helpers::delete_file($profile->image);
            $profile->update([
                "image" => Helpers::upload_files($request->image, '/uploads/profile/'),
            ]);
        }
        $profile->update([
            "name" => $request->name,
            "email" => $request->email,
        ]);
        if ($request->filled('role')) {
            $profile->syncRoles([$request->role]); // This removes old roles and assigns the new one
        }
        session()->flash("success", __("messages.Updated_successfully"));
        return back();
    }
    public function update_password($profile_id,Request $request){
        $request->validate([
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8',
            "old_password" => "required"
        ]);

        $modelType = $request->input('model');
        // Map the model types to actual classes
        $models = [
            'user' => User::class,
            'admin' => Admin::class,
            'provider' => Providers::class,
        ];
        // Check if the provided model type is valid
        if (!array_key_exists($modelType, $models)) {
            session()->flash('error', __('Invalid model type'));
            return back();
        }
        // Fetch the user using the specified model
        $modelClass = $models[$modelType];
        $profile = $modelClass::find($profile_id);
    
        // If the user doesn't exist, return an error
        if (!$profile) {
            session()->flash('error', __('Record not found'));
            return back();
        }
        if($request->new_password != $request->confirm_password){
            return redirect()->back()->with('error', 'Password  does not match');
        }
        if(!Hash::check($request->old_password, $profile->password)){
            return redirect()->back()->with('error', 'Old Password is Incorrect');
        }
        $profile->password = Hash::make($request->new_password);
        $profile->save();
        return redirect()->back()->with('success', 'Password Updated Successfully');
    }
}
