<?php

namespace App\Http\Controllers\Admin;

use App\Models\Requests;
use Illuminate\Http\Request;
use App\Services\LocationService;
use App\Http\Controllers\Controller;

class RequestsController extends Controller
{
    public $model_view_folder;
    private $LocationService;
    public function __construct(LocationService $LocationService)
    {
        $this->middleware(['permission:view users'])->only('index');
        $this->middleware(['permission:update users'])->only('update');
        $this->middleware(['permission:delete users'])->only('destroy');
        $this->model_view_folder = 'admin.requests.';
        $this->LocationService = $LocationService;
    }
    public function index($type, Request $request)
    {
        $type = str_replace("-", "_", $type);
        $requests = Requests::where("type", $type)->orderBy("id", "DESC")
            ->when($request->status, function ($q) use ($request) {
                $q->where("status", $request->status);
            })
            ->paginate(15);
        return view($this->model_view_folder . "index")
            ->with("requests", $requests)
            ->with("section", $type)
        ;
    }
    public function show($request_id)
    {
        $request = Requests::with(["user", "provider"])->findOrFail($request_id);

        // ✅ Pickup AR
        if (is_null($request->pickup_address_ar) && $request->pickup_lat && $request->pickup_lng) {
            $request->pickup_address_ar = $this->LocationService->get_location_name($request->pickup_lat, $request->pickup_lng, 'ar');
        }

        // ✅ Pickup EN
        if (is_null($request->pickup_address_en) && $request->pickup_lat && $request->pickup_lng) {
            $request->pickup_address_en = $this->LocationService->get_location_name($request->pickup_lat, $request->pickup_lng, 'en');
        }

        // ✅ Dropoff AR
        if (is_null($request->dropoff_address_ar) && $request->dropoff_lat && $request->dropoff_lng) {
            $request->dropoff_address_ar = $this->LocationService->get_location_name($request->dropoff_lat, $request->dropoff_lng, 'ar');
        }

        // ✅ Dropoff EN
        if (is_null($request->dropoff_address_en) && $request->dropoff_lat && $request->dropoff_lng) {
            $request->dropoff_address_en = $this->LocationService->get_location_name($request->dropoff_lat, $request->dropoff_lng, 'en');
        }

        // ✅ حفظ لو في تغييرات
        if ($request->isDirty([
            'pickup_address_ar',
            'pickup_address_en',
            'dropoff_address_ar',
            'dropoff_address_en',
        ])) {
            $request->save();
        }
        return view($this->model_view_folder . "show")
            ->with("request_details", $request);
    }
}
