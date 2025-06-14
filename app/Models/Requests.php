<?php

namespace App\Models;

use App\Models\Negotiations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requests extends Model
{
    use HasFactory;
    protected $table="requests";
    protected $guarded=[];
    public function offers()
    {
        return $this->hasMany(Offers::class, 'request_id');
    } 
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    

    public function service(){
        return $this->belongsTo(Services::class,"service_id");
    }
    public function coupon(){
        return $this->belongsTo(Coupon::class,"coupon_id");
    }
    public function provider(){
        return $this->belongsTo(Providers::class,"provider_id");
    }
    public function canceledBy()
    {
        return $this->morphTo();
    }
    protected $hidden = [
        'admin_profit',
    ];
    
}
