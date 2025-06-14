<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $model_view_folder;

    //default namespace view files

    public function __construct()
    {
        $this->middleware(['permission:view users'])->only('index');
        $this->middleware(['permission:update users'])->only('update');
        $this->middleware(['permission:delete users'])->only('destroy');
         $this->model_view_folder = 'admin.users.';
    }
    public function index(){
        $users=User::orderBy("id","DESC")->paginate(15);
        return view($this->model_view_folder . "index")
        ->with("users",$users);
    }
    public function show($user_id){
        $user=User::where("id",$user_id)->with(['requests'])->first();
        if(! $user)
            return back();
        return view($this->model_view_folder."show")
        ->with("user",$user)
        ;
    }
    public function store(Request $request)
    {
        $request->validate([
            "password" => "required|min:8", 
            "name" => "required|string|max:255", 
            "email" => "required|email|unique:users,email", 
            "phone" => "required|unique:users,phone", 
        ]);
        $user=User::create([
            "password"=> bcrypt($request->password),
            "name"=>$request->name,
            "email"=>$request->email,
            "phone"=>$request->phone,
            "image"=>$request->hasFile("avatar") ?  Helpers::upload_files($request->avatar,'/uploads/users/') : null,
        ]);
        Toastr::success(trans('messages.added_successfully'));
        return back();

    }
    public function delete($user_id)  {
        $user=User::find($user_id);
        Helpers::delete_file($user->image);
        $user->delete();   
        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
    public function update(Request $request){
        $request->validate([
            "user_id"=>"required",
            "name"=>"required",
            "phone"=>"required",
            "email"=>"required",
        ]);
        $user=User::find($request->user_id);
        if(! $user)
            return back();
        
        if($request->hasFile("avatar")){
            Helpers::delete_file($user->image);
            $user->update([
                "image"=>Helpers::upload_files($request->avatar,'/uploads/users/'),
            ]);
        }

        $user->update([
            "name"=>$request->name,
            "phone"=>$request->phone,
            "email"=>$request->email,
        ]);
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
}
