<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRooms extends Model
{
    use HasFactory;
    protected $table="chat_rooms";
    protected $guarded=[];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function provider()
    {
        return $this->belongsTo(Providers::class, 'provider_id');
    }
    public function request()
    {
        return $this->belongsTo(Requests::class, 'request_id');
    }
    public function messages(){
        return $this->hasMany(ChatMessages::class,"room_id");
    }
}
