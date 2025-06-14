<?php

namespace App\Models;

use App\Models\Providers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Processes extends Model
{
    use HasFactory;
    protected $table = 'processes';
    protected $fillable = ['provider_id', 'process_number', 'name', 'rejcet_reason', 'status'];
    public function provider()
    {
        return $this->belongsTo(Providers::class,'provider_id');
    }
}
