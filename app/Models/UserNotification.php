<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    public $guarded = [];
    protected $appends = ['image_path'];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expiry_date' => 'datetime:Y-m-d',
    ];

    public function getImagePathAttribute()
    {
        if ($this->image) {
            return asset('uploads/notifications/' . $this->image);
        }

        return asset('admin/avatar.jpg');

    }//
}
