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
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public $model_view_folder;

    //Test
    public function __construct()
    {
        $this->middleware(['permission:view admins'])->only('index');
        $this->middleware(['permission:update admins'])->only('update');
        $this->middleware(['permission:delete admins'])->only('destroy');
        $this->model_view_folder = 'admin.admins.';
    }
    public function index()
    {
        $admins = Admin::orderBy("id", "DESC")->get();
        $roles = Role::all();
        return view($this->model_view_folder . "index")
            ->with("admins", $admins)
            ->with("roles", $roles)
        ;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:admins,phone',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
            "country_code"=>"required",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_type', 'add');
        }
        $admin = Admin::create([
            "email" => $request->email,
            "phone" =>"+". $request->country_code . $request->phone,
            "name" => $request->name,
            "password" => Hash::make($request->password),
            
        ]);

        if ($request->file('avatar')) {
            $admin->update([
                "image" => Helpers::upload_files($request->avatar, '/uploads/admins/')
            ]);
        }
        $role = Role::findOrFail($request->role);
        $admin->assignRole($role->name);

        Toastr::success(trans('messages.Added_successfully'));
        return back();
    }
    public function delete($admin_id)
    {
        $admin = Admin::find($admin_id);
        Helpers::delete_file($admin->image);
        $admin->delete();
        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
    public function update(Request $request)
    {
        $admin = Admin::findOrFail($request->admin_id);
        if (! $admin)
            return back();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:admins,phone,' . $admin->id,
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'role' => 'required',
                        "country_code"=>"required",

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_type', 'edit')
                ->with('edit_id', $admin->id);
        }


        if ($request->hasFile("avatar")) {
            Helpers::delete_file($admin->image);
            $admin->update([
                "image" => Helpers::upload_files($request->avatar, '/uploads/admins/'),
            ]);
        }
        if($request->filled("password")){
            $admin->update([
                "password"=>Hash::make($request->password),
            ]);
        }

        $admin->update([
            "name" => $request->name,
            "phone" =>"+". $request->country_code . $request->phone,
            "email" => $request->email,
        ]);
        if ($request->has("role")) {
            $role = Role::findOrFail($request->role);
            $admin->assignRole($role->name);
        }
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }

    public function change_language($lang)
    {
        App::setLocale(session($lang)); // Set locale from session
        session()->put('lang', $lang);
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
}
