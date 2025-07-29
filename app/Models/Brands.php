<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use HasFactory;
    protected $table="brands";
    protected $guarded=[];
    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    } 
    public function models()
    {
        return $this->hasMany(Models::class, 'brand_id');
    } 
}
