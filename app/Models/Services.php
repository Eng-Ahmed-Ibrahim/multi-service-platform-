<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $table="services";
    protected $guarded=[];
    public function brands()
    {
        return $this->hasMany(Brands::class, 'service_id');
    } 
}
