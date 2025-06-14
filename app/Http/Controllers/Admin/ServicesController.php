<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use App\Models\Services;
use Illuminate\Support\Facades\Hash;

class ServicesController extends Controller
{
    public $model_view_folder;
    public $services_path="/uploads/services/handyman/";

    public function __construct()
    {
        $this->middleware(['permission:view users'])->only('index');
        $this->middleware(['permission:update users'])->only('update');
        $this->middleware(['permission:delete users'])->only('destroy');
         $this->model_view_folder = 'admin.services.';
    }
    public function index($section){
        $section=str_replace("-","_",$section);
        $services=Services::where("section",$section)->orderBy("id","DESC")->paginate(15);
        return view($this->model_view_folder . "index")
        ->with("section",$section)
        ->with("services",$services);
    }
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255", 
            "name_ar" => "required|string|max:255", 
            "image"=>"required",
            "commission"=>"required",
            "section"=>"required",
        ]);
        Services::create([
            "name"=>$request->name,
            "name_ar"=>$request->name_ar,
            "commission"=>$request->commission,
            "section"=>$request->section,
            "image"=>Helpers::upload_files($request->image,$this->services_path),
        ]);
        Toastr::success(trans('messages.Added_successfully'));
        return back();
    }
    public function delete($service_id)  {
        $service=Services::find($service_id);
        Helpers::delete_file($service->image);
        $service->delete();   
    
        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
    public function update(Request $request){
        $request->validate([
            "service_id"=>"required",
            "name"=>"required",
            "commission"=>"required",
            "name_ar"=>"required",
        ]);
        $service=Services::find($request->service_id);
        if(! $service)
            return back();

        if($request->hasFile("image")){
            Helpers::delete_file($service->image);
            $service->update([
                "image"=>Helpers::upload_files($request->image,$this->services_path),
            ]);
        }
        $service->update([
            "name"=>$request->name,
            "name_ar"=>$request->name_ar,
            "commission"=>$request->commission,
        ]);
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
    public function update_status(Request $request){
        $request->validate([
            "service_id"=>"required",
        ]);
        $service=Services::find($request->service_id);
        if(! $service)
            return back();
        $service->update([
            "status"=>$service->status == true ? false : true ,
        ]);
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }

}
