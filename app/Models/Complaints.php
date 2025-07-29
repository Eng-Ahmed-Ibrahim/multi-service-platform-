<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    use HasFactory;
    protected $table = 'complaints';
    protected $guarded=[];
    public function sender()
    {
        return $this->morphTo();
    }
}
