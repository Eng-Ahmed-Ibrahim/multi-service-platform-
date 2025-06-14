<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;

class BusinessSettingController extends Controller
{
    public $model_view_folder;
    public function __construct()
    {
        return $this->model_view_folder = 'admin.settings.';

    }
    public function index(){
        $settings=BusinessSetting::all();
        return view($this->model_view_folder . 'index')
        ->with("settings",$settings)
        ;

    }
    public function update(Request $request)
    {
        $request->validate([
            "keys" => "required|array",
            "values" => "required|array",
        ]);
    
        foreach ($request->keys as $index => $key) {
            BusinessSetting::where('key', $key)->update([
                'value' => $request->values[$index]
            ]);
        }
        session()->put("settings_tab", $request->tab);

        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
    
}
