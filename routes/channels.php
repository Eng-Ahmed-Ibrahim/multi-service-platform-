<?php

use App\Models\ChatRooms;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-message.{room}', function ($user, ChatRooms $room) {
    return $room && ($user->id === $room->user_id || $user->id === $room->provider_id);
});

Broadcast::channel('requests.drivers.{providerId}', function ($user,$providerId) {
    return $user->role === 'driver' && $user->id === (int) $providerId; 
});

Broadcast::channel('requests.handymans.{providerId}', function ($user,$providerId) {
    return $user->role === 'handyman' && $user->id === (int) $providerId; 
});

Broadcast::channel('new_offers.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
Broadcast::channel('new_offers.{userId}.{requestId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
Broadcast::channel('push-driver-to-map.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('offers-status.{providerId}.{requestId}', function ($user, $providerId) {
    return (int) $user->id === (int) $providerId && ($user->role === 'driver' || $user->role === 'handyman'); 
});


Broadcast::channel('test-channel', function ($user) {
    return true;
});

Broadcast::channel('online-users', function ($user) {
    return ['id' => $user->id, 'name' => $user->name, 'role' => $user->role];
});
