<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Coupon;
use App\Models\Reviews;
use App\Helpers\Helpers;
use App\Models\Requests;
use App\Models\ChatRooms;
use App\Models\Documents;
use App\Models\FcmTokens;
use App\Models\Processes;
use App\Models\Providers;
use Illuminate\Http\Request;
use App\Models\CancelationReasons;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

class ApiController extends Controller
{
    use ResponseTrait;

    public function cancel_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required|exists:requests,id",
            "cancelation_reason_id" => "required"
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors()->first(), __("messages.Validation Error"), 422);
        $CuRequest = Requests::find($request->request_id);
        if (!$CuRequest)
            return $this->Response(null, "Request not found", "Request not found", 404);

        $CuRequest->update([
            "status" => "cancelled",
            "cancelation_reason_id" => $request->cancelation_reason_id,
            "canceled_by_id" => $request->user()->id,
            "canceled_by_type" => get_class($request->user()),
        ]);

        if ($CuRequest->provider != null) {

            $data = [
                "user" => $CuRequest->provider,
                "title" => "User Just Cancelled Request",
                "description" => "User Cancelled Request",
                "model_type" => "cancel_request",
                "model_id" =>$CuRequest->id
            ];
            Helpers::push_notification($data);
        }

        return response()->json(["message" => "Request cancelled successfully"]);
    }
    public function request_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required|exists:requests,id"
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors()->first(), __("messages.Validation Error"), 422);
        $details = Requests::where("id", $request->request_id)
            ->with(['user', 'provider', 'service'])->first();
        if(!$details)
            return $this->Response(null,"Request Not Found",422);
        if ($details->provider_id != null) {
            $room = ChatRooms::where("request_id", $details->id)
                ->where("user_id", $details->user_id)
                ->where("provider_id", $details->provider_id)->first();
            $details["room_id"] = $room->id ?? null; 
        } else {
            $details["room_id"] = null;
        }
        return $this->Response($details, 'Request Details', 200);
    }
    public function cancelation_reasons()
    {
        $data = CancelationReasons::all();
        return $this->Response($data, "Cancelation reasons", 200);
    }
    public function history(Request $request)
    {
        $role = $request->user()->role;
        $query = Requests::query();
        if ($role == "user")
            $query->where("user_id", $request->user()->id)->with(["provider"]);
        else
            $query->where("provider_id", $request->user()->id)->with(["user"]);

        if($request->filled("type"))
            $query->where('type',$request->type);
        $history = $query
            ->whereIn("status", ["cancelled", "completed"])
            ->orderBy("id", 'DESC')
            ->with(["service:id,name,name_ar,image"])
            ->get();
        foreach ($history as $item) {
            if ($item->provider && $item->provider->carPhoto) {
                $item->provider->car_photo = $item->provider->carPhoto->attachment; // Assuming 'attachment' holds the file path
                $item->provider->brand_name = $item->provider->brand->name;
                $item->provider->model_name = $item->provider->model->name;
                unset($item->provider->carPhoto); // Remove the extra relation from response
                unset($item->provider->brand); // Remove the extra relation from response
                unset($item->provider->model); // Remove the extra relation from response
            }
        }

        return $this->Response($history, "History", 201);
    }
    public function getProviderReviews(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "provider_id"=>"required|exists:providers,id",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),__("messages.Validation Error"),422);
        $reviews = Reviews::where('provider_id', $request->provider_id)->get();

        $averageRating = $reviews->avg('rating');
        $data=[
            'average_rating' => round($averageRating, 2),
            'total_reviews' => $reviews->count(),
            'reviews' => $reviews
        ];
        return $this->Response($data,'Reviews',200);
    }
    public function coupons(Request $request){
        $coupons=Coupon::orderBy("id","DESC")->get();
        $data=[
            "coupons"=>$coupons,
        ];
        return $this->Response($data,"Coupons",201);
    }
    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "device_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors()->first(), __("messages.Validation Error"), 422);
        $fcms=FcmTokens::where("device_id",$request->device_id)
        ->where("account_id",$request->user()->id)
        ->where("account_type",get_class($request->user()))
        ->get();
        if($fcms){
            foreach($fcms as $fcm){
                $fcm->delete();
            }
        }
        $request->user()->tokens()->delete();

        return $this->Response(null, __("messages.Logged Out Successfully"), 200);
    }
}
