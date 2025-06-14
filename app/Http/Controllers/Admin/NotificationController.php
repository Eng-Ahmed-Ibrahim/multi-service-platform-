<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;

class NotificationController extends Controller
{
    public $model_view_folder;

    //default namespace view files

    public function __construct()
    {
        $this->middleware(['permission:view handymans'])->only('index');
        $this->middleware(['permission:update handymans'])->only('update');
        $this->middleware(['permission:delete handymans'])->only('destroy');
         $this->model_view_folder = 'admin.notification.';
    }
    public function index(){
        $notifications=Notification::where("to","!=",null)->orderBy("id","DESC")->get();
        return view($this->model_view_folder ."index")
        ->with("notifications",$notifications)
        ;
    }
    public function store(Request $request){
        $request->validate([
            "subject"=>"required",
            "message"=>"required",
            "to"=>"required|in:all,user,driver,handyman",
        ]);
        Helpers::push_notification([
            "subject"=>$request->subject,
            "message"=>$request->message,
            "to"=>$request->to
        ]);
        Toastr::success(__("messages.Added_successfully"));
        return back();
    }
}
