<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="offers";
    public function ride_request()
    {
        return $this->belongsTo(Requests::class, 'request_id');
    }
    public function provider()
    {
        return $this->belongsTo(Providers::class, 'provider_id');
    }
    public function request(){
        return $this->belongsTo(Requests::class,"request_id");
    }
}
