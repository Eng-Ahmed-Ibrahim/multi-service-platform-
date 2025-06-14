<?php

namespace App\Models;

use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id','name', 'email', 'phone','password','type', 'image', 'area_id', 'firebase_token'];

    protected $appends = ['image_path'];
    protected $guard = 'admin';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     //atr


    //  public function getNameAttribute($value)
    //  {
    //      return ucfirst($value);

    //  }// end of getNameAttribute

    //  public function getCreatedAtAttribute($value)
    //  {
    //      return date('Y-M-d',strtotime($value));
    //  }

    //  public function getImagePathAttribute()
    //  {
    //      if ($this->image) {
    //          return asset('uploads/admins/' . $this->image);
    //      }

    //      return asset('admin/avatar.jpg');

    //  }//

    // public function getRoleAttribute()
    // {
    //     return $this->role;
    // }


}
