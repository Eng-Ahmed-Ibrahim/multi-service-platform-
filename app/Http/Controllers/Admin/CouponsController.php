<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponsController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy("id", "DESC")->paginate(15);
        return view('admin.coupons.index')
            ->with("coupons", $coupons);
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                "coupon_code" => 'required',
                "coupon_value" => 'required',
                "start_at" => 'required',
                "end_at" => 'required',
                "type" => 'required',
            ],
            [
                "coupon_code.required" => __('messages.Validate_name_ar'),
                "coupon_value.required" => __('messages.Validate_name_en'),
                "start_at.required" => __('messages.Validate_commission_value'),
                "end_at.required" => __('messages.Validate_commission_value'),
                "type.required" => __('messages.Validate_commission_value'),
            ]
        );
        if($request->type=="percentage" && $request->coupon_value > 100 || $request->coupon_value < 0){
            session()->flash("error",__("messages.Coupon_0_100"));
            return back();
        }
        $startAt = Carbon::createFromFormat('Y-m-d', $request->start_at)->format('m/d/Y');
        $endAt = Carbon::createFromFormat('Y-m-d', $request->end_at)->format('m/d/Y');
        if($endAt < $startAt){
            session()->flash("error",__("messages.Start_at_after_end_at"));
            return back();
        }
        Coupon::create([
            "coupon_code" => $request->coupon_code,
            "coupon_value" => $request->coupon_value,
            "start_at" => $startAt,
            "end_at" => $endAt,
            "type" => $request->type,
        ]);
        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate(
            [
                "id" => "required",
                "coupon_code" => 'required',
                "coupon_value" => 'required',
                "start_at" => 'required',
                "end_at" => 'required',
                "type" => 'required',
            ],
            [
                "coupon_code.required" => __('messages.Validate_name_ar'),
                "coupon_value.required" => __('messages.Validate_name_en'),
                "start_at.required" => __('messages.Validate_commission_value'),
                "end_at.required" => __('messages.Validate_commission_value'),
                "type.required" => __('messages.Validate_commission_value'),
            ]
        );
        if($request->type=="percentage" && $request->coupon_value > 100 || $request->coupon_value < 0){
            session()->flash("error",__("messages.Coupon_0_100"));
            return back();
        }
        $commission = Coupon::find($request->id);
        if ($commission) {
            $commission->update([
                "coupon_code" => $request->coupon_code,
                "coupon_value" => $request->coupon_value,
                "start_at" => $request->start_at,
                "end_at" => $request->end_at,
                "type" => $request->type,
            ]);
            session()->flash("success", __('messages.Updated_successfully'));
            return back();
        } else {
            return back();
        }
    }
    public function destroy(Request $request)
    {
        $commission = Coupon::find($request->id);
        if ($commission) {
            $commission->delete();
            session()->flash("success", __("messages.Deleted_successfully"));
            return back();
        } else {
            return back();
        }
    }
}
