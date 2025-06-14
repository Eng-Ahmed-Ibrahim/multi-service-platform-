<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yoeunes\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public $model_view_folder;

    //default namespace view files hello world this ahmed ebrahim

    public function __construct()
    {
        $this->middleware(['permission:view admins'])->only('index');
        $this->middleware(['permission:update admins'])->only('update');
        $this->middleware(['permission:delete admins'])->only('destroy');
         $this->model_view_folder = 'admin.admins.';
    }
    public function index(){
        $admins=Admin::orderBy("id","DESC")->get();
        $roles=Role::all();
        return view($this->model_view_folder . "index")
        ->with("admins",$admins)
        ->with("roles",$roles)
        ;
    }
    public function store(Request $request)
    {
        $admin = Admin::create([
            "id"=>1001,
            "email"=>$request->email,
            "phone"=>$request->phone,
            "password"=>Hash::make($request->password),
        ]);

        if($request->file('avatar')){
            $admin->update([
                "image"=>Helpers::upload_files($request->avatar,'/uploads/admins/')
            ]);
        }
        $role = Role::findOrFail($request->role);
        $admin->assignRole($role);

        Toastr::success(trans('messages.Added_successfully'));
        return back();
        // try {
        //     DB::beginTransaction();


        // }catch (\Exception $exception){
        //     DB::rollback();
        //     Toastr::error(trans('messages.general_error'));
        //     return session()->flash('error', __('messages.general_error'));
        // }
    }
    public function delete($admin_id)  {
        $admin=Admin::find($admin_id);
        Helpers::delete_file($admin->image);
        $admin->delete();   
        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
    public function update(Request $request){
        $request->validate([
            "admin_id"=>"required",
            "name"=>"required",
            "phone"=>"required",
            "email"=>"required",
            "role"=>"required",
        ]);
        $admin=Admin::find($request->admin_id);
        if(! $admin)
            return back();
        
        if($request->hasFile("avatar")){
            Helpers::delete_file($admin->image);
            $admin->update([
                "image"=>Helpers::upload_files($request->avatar,'/uploads/admins/'),
            ]);
        }

        $admin->update([
            "name"=>$request->name,
            "phone"=>$request->phone,
            "email"=>$request->email,
        ]);
        if($request->has("role")){
            $role = Role::findOrFail($request->role);
            $admin->assignRole($role);
        }
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
    
    public function change_language($lang){
        App::setLocale(session($lang)); // Set locale from session
        session()->put('lang', $lang);
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
}
