<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yoeunes\Toastr\Facades\Toastr;

class AuthController extends Controller
{
    public function login_form(){
        return view('admin.login_form');
    }

    public function login(Request $request)  {
        $request->validate([
            'email'=>'required',
            'password'=>"required",
        ]);
        $credentials = $request->only('email', 'password');
        $user = Admin::where('email', $credentials['email'])->first();


        if ($user && Hash::check($credentials['password'], $user->password)) {
            if($user->power!='provider' && $user->power != "provider"){
                Auth::guard("admin")->login($user,true);   
                return redirect()->route('admin.dashboard');
            }else{
                session()->flash('error','Not Allowed To Login ');
                return back();
            }
        }
        Toastr::error("The phone or password is incorrect");
        return back();
    }
    public function logout(Request $request)
    {
        Auth::guard("admin")->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect("/");
    }
}
