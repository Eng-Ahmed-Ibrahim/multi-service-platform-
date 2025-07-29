<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use App\Helpers\Helpers;
use App\Models\Services;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;

class BrandsController extends Controller
{
    public $brand_view_folder;
    public $services_path="/uploads/services/brands/";

    public function __construct()
    {
        $this->middleware(['permission:view users'])->only('index');
        $this->middleware(['permission:update users'])->only('update');
        $this->middleware(['permission:delete users'])->only('destroy');
         $this->brand_view_folder = 'admin.services.';
    }
    public function index( $service_id){
        $service=Services::where("id",$service_id)->with(['brands'=>function($q){
            $q->orderBy("id","DESC");
        }])->first();
        return view($this->brand_view_folder."brands")
        ->with("service",$service)
        ;
    }
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255", 
            "name_ar" => "required|string|max:255", 
            "image"=>"required",
            "service_id"=>"required|exists:services,id",
        ]);
        $brand=Brands::create([
            "service_id"=>$request->service_id,
            "name"=>$request->name,
            "name_ar"=>$request->name_ar,
            "image"=>Helpers::upload_files($request->image,$this->services_path),
        ]);
        Helpers::recache_brands($request->service_id);
        Toastr::success(trans('messages.Added_successfully'));
        return back();
    }
    public function delete($brand_id)  {
        $brand=Brands::findOrFail($brand_id);
        $service_id=$brand->service_id;
        Helpers::delete_file($brand->image);
        $brand->delete();   
    
        Helpers::recache_brands($service_id);
        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
    public function update(Request $request){
        $request->validate([
            "brand_id"=>"required",
            "name"=>"required",
            "name_ar"=>"required",
        ]);
        $brand=Brands::findOrFail($request->brand_id);


        if($request->hasFile("image")){
            Helpers::delete_file($brand->image);
            $brand->update([
                "image"=>Helpers::upload_files($request->image,$this->services_path),
            ]);
        }
        $brand->update([
            "name"=>$request->name,
            "name_ar"=>$request->name_ar,
        ]);
        Helpers::recache_brands($brand->service_id);

        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
    public function update_status(Request $request){
        $request->validate([
            "brand_id"=>"required",
        ]);
        $brand=Brands::findOrFail($request->brand_id);

        $brand->update([
            "status"=>$brand->status == true ? false : true ,
        ]);
                Helpers::recache_brands($brand->service_id);

        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
}
