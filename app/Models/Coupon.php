<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable=['coupon_code','coupon_value','type','start_at','end_at'];

    /*
        public function getEndAtAttribute($value)
        {
    
            return \Carbon\Carbon::parse($value)->diffForHumans();
        }
    */
}
