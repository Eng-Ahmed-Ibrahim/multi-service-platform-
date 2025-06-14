<?php

namespace App\Models;

use App\Models\UserRequestRating;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Providers extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // use SoftDeletes;
    protected $guarded = [];
    protected $table = "providers";
    public function documents()
    {
        return $this->hasMany(Documents::class, 'provider_id');
    }
    public function carType()
    {
        return $this->belongsTo(Services::class, 'car_type');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brands::class, 'brand_id');
    }
    public function processes()
    {
        return $this->hasMany(Processes::class, 'provider_id');
    }
    public function walletTransactions()
    {
        return $this->morphMany(WalletTransaction::class, 'accountable');
    }

    public function model()
    {
        return $this->belongsTo(Models::class, 'model_id');
    }
    public function services()
    {
        return $this->hasMany(ProviderService::class, 'provider_id');
    }

    public function requests()
    {
        return $this->hasMany(Requests::class, 'provider_id');
    }
    public function complaints()
    {
        return $this->morphMany(Complaints::class, 'sender');
    }
    public function reviews()
    {
        return $this->hasMany(Reviews::class, "provider_id");
    }
    public function carPhoto()
    {
        return $this->hasOne(Documents::class, 'provider_id')
            ->where('name', 'car_photo');
    }
    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->gt(now()->subSeconds(15));
    }

    protected $hidden = [
        'password',
        'remember_token', // Optional, if you are using it
        "created_at",
        "updated_at",
        "deleted_at",
    ];
}
