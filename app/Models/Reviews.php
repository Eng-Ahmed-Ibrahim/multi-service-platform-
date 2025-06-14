<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;
    protected $table = "reviews";
    protected $fillable = ["user_id","provider_id","rating","message"];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function provider(){
        return $this->belongsTo(Providers::class,"provider_id");
    }   
}
