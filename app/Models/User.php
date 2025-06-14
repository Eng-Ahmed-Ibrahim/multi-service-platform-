<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $guarded = [];
    protected $guard = 'user';
    public function provider()
    {
        return $this->belongsTo(Providers::class, 'provider_id');
    }
    public function walletTransactions()
    {
        return $this->morphMany(WalletTransaction::class, 'accountable');
    }

    public function requests()
    {
        return $this->hasMany(Requests::class, 'user_id');
    }
    public function complaints()
    {
        return $this->morphMany(Complaints::class, 'sender');
    }

    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->gt(now()->subSeconds(15));
    }

    protected $hidden = [
        'password',
        'remember_token',
        "created_at",
        "updated_at",
        "deleted_at",
    ];
}
