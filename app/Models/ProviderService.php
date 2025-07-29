<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    use HasFactory;
    protected $table="provider_services";
    protected $guarded=[];
    public function provider()
    {
        return $this->belongsTo(Providers::class, 'provider_id');
    } 
    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    } 
}
