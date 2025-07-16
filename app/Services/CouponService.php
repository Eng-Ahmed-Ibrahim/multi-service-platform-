<?php

namespace App\Services;

use Carbon\Carbon;

class CouponService{
        
    public  function apply_coupon($coupon,$price){

        if (Carbon::now()->gt(Carbon::parse($coupon->end_at))) {
            return ['false'=>false,'message'=>'Coupon has expired'];
        }
        $discount_value=0;
        $final_price=0;

        $max_commission_percentage= getBusinessSetting("profit_percentage") ;
        $max_commission_amount =   ($price * $max_commission_percentage )/100 ;
        
        if($coupon->type=="percentage"){
            $coupon_value=$coupon->coupon_value <= $max_commission_percentage ? $coupon->coupon_value : $max_commission_percentage ;
            $discount_value=($price * $coupon_value )/100;
            $final_price= $price - $discount_value;
        }elseif($coupon->type=="amount"){
            $discount_value =  $coupon->coupon_value <= $max_commission_amount ? $coupon->coupon_value :  $max_commission_amount ;
            $final_price= $price - $discount_value;
        }
        return [
            "final_price"=>$final_price,
            "discount_value"=>  $discount_value,
            "admin_profit"=> $max_commission_amount - $discount_value,
            "coupon_id"=>$coupon->id
        ];
    }

}