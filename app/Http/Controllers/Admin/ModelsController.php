<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use App\Models\Models;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;

class ModelsController extends Controller
{
    public $model_view_folder;
    public $services_path = "/uploads/services/brands/models/";

    public function __construct()
    {
        $this->middleware(['permission:view users'])->only('index');
        $this->middleware(['permission:update users'])->only('update');
        $this->middleware(['permission:delete users'])->only('destroy');
        $this->model_view_folder = 'admin.services.';
    }
    public function index($brand_id)
    {
        $service = Brands::where("id", $brand_id)->with(['models' => function ($q) {
            $q->orderBy("id", "DESC");
        }])->first();
        return view($this->model_view_folder . "models")
            ->with("service", $service);
    }
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "name_ar" => "required|string|max:255",
            "image" => "required",
            "brand_id" => "required|exists:brands,id",
        ]);
        Models::create([
            "brand_id" => $request->brand_id,
            "name" => $request->name,
            "name_ar" => $request->name_ar,
            "image" => Helpers::upload_files($request->image, $this->services_path),
        ]);
        Helpers::recache_models($request->brand_id);
        Toastr::success(trans('messages.Added_successfully'));
        return back();
    }
    public function delete($model_id)
    {
        $model = Models::find($model_id);
        $brand_id = $model->brand_id;
        Helpers::delete_file($model->image);
        $model->delete();
        Helpers::recache_models($brand_id);

        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate([
            "model_id" => "required",
            "name" => "required",
            "name_ar" => "required",
        ]);
        $model = Models::find($request->model_id);
        if (! $model)
            return back();

        if ($request->hasFile("image")) {
            Helpers::delete_file($model->image);
            $model->update([
                "image" => Helpers::upload_files($request->image, $this->services_path),
            ]);
        }
        $model->update([
            "name" => $request->name,
            "name_ar" => $request->name_ar,
        ]);
        Helpers::recache_models($model->brand_id);

        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
    public function update_status(Request $request)
    {
        $request->validate([
            "model_id" => "required",
        ]);
        $model = Models::find($request->model_id);
        if (! $model)
            return back();
        $model->update([
            "status" => $model->status == true ? false : true,
        ]);
        Helpers::recache_models($model->brand_id);

        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
}
