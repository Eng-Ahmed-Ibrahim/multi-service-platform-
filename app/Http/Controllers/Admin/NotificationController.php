<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Models\Notification;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public $model_view_folder;

    //default namespace view files

    public function __construct()
    {
        $this->model_view_folder = 'admin.notification.';
    }
    public function index()
    {
        $notifications = Notification::where("to", "!=", null)->orderBy("id", "DESC")->paginate(20);
        return view($this->model_view_folder . "index")
            ->with("notifications", $notifications);
    }
    public function store(Request $request)
    {
        $request->validate([
            "subject" => "required",
            "message" => "required",
                       "subject_ar" => "required",
            "message_ar" => "required",
            "to" => "required|in:all,user,driver,handyman",
        ]);
        Notification::create([
            "subject" => $request->subject,
            "subject_ar" => $request->subject_ar,
            "message" => $request->message,
            "message_ar" => $request->message_ar,
            "to" => $request->to,
        ]);
        Toastr::success(__("messages.Added_successfully"));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate([
            "notification_id" => "required|exists:notifications,id",
            "subject" => "required",
            "message" => "required",
            "subject_ar" => "required",
            "message_ar" => "required",
            "to" => "required|in:all,user,driver,handyman",
        ]);
        $notification = Notification::findOrFail($request->notification_id);
        $notification->update([
            "subject" => $request->subject,
            "subject_ar" => $request->subject_ar,
            "message" => $request->message,
            "message_ar" => $request->message_ar,
            "to" => $request->to,
        ]);
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
    public function delete($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
}
