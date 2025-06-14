<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helpers;
use App\Models\User;
use App\Models\Requests;
use App\Models\Providers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;
use App\Models\Documents;
use App\Models\Processes;

class ApiController extends Controller
{
    use ResponseTrait;

    public function cancel_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required|exists:requests,id",
            "reason" => "required"
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors()->first(), "Validation Error", 422);

        $CuRequest = Requests::find($request->request_id);
        if (!$CuRequest)
            return $this->Response(null, "Request not found", "Request not found", 404);

        $CuRequest->update([
            "status" => "cancelled",
            "cancelation_reason" => $request->reason,
            "canceled_by_id" => $request->user()->id,
            "canceled_by_type" => get_class($request->user()),
        ]);

        return response()->json(["message" => "Request cancelled successfully"]);
    }
    public function request_details(Request $request){
        $validator=Validator::make($request->all(),[
            "request_id"=>"required|exists:requests,id"
        ]);
        if($validator->fails())
            return $this->Response($validator->errors()->first(), "Validation Error", 422);
        $details=Requests::where("id",$request->request_id)
        ->with(['user','provider','service'])->first();
        return $this->Response($details,'Request Details',200);
    }
    public function cancelation_reasons()
    {
        $data = [
            [
                "id" => 1,
                "reason" => "I no longer need the service",
                "reason_ar" => "لم اعد بحاجة للخدمة"
            ],
            [
                "id" => 2,
                "reason" => "I found another service provider",
                "reason_ar" => "وجدت مقدم خدمة اخر"
            ],
            [
                "id" => 3,
                "reason" => "I am not satisfied with the service",
                "reason_ar" => "لم اكن راضي عن الخدمة"
            ],
            [
                "id" => 4,
                "reason" => "I am not satisfied with the service provider",
                "reason_ar" => "لم اكن راضي عن مقدم الخدمة"
            ],
            [
                "id" => 5,
                "reason" => "Other",
                "reason_ar" => "اخرى"
            ],

        ];
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

        $history = $query
        ->whereIn("status", ["cancelled", "accepted"])
        ->orderBy("id", 'DESC')
            ->get();
        // Ensure car_photo is properly formatted in the response
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


}
