<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;
    protected $table="documents";
    protected $guarded=[];
    public function provider()
    {
        return $this->belongsTo(Providers::class, 'provider_id');
    } 
}
