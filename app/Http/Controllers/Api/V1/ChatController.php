<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helpers;
use App\Models\ChatRooms;
use App\Events\MessageEvent;
use App\Models\ChatMessages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

class ChatController extends Controller
{
    use ResponseTrait;
    private function getOrCreateChatRoom($userId, $providerId, $requestId)
    {
        return ChatRooms::firstOrCreate([
            "request_id" => $requestId,
            "provider_id" => $providerId,
            "user_id" => $userId,
        ])->load(['messages' => function ($query) {
            $query
            ->select('id', 'room_id', 'message', 'sender_id', 'sender_type', 'created_at', 'read_at')
            ;
        }]);
        
    }
    private function getUserAndProviderId($request)
    {
        $provider_id = null;
        $user_id = null;
        $type = get_class($request->user());
        if ($type == "App\Models\User") {
            $user_id = $request->user()->id;
            $provider_id = $request->receiver_id;
        } else {
            $provider_id = $request->user()->id;
            $user_id = $request->receiver_id;
        }
        return [$user_id, $provider_id];
    }

    public function get_messages(Request $request){
        $validator = Validator::make($request->all(), [
            "request_id" => "required",
            "receiver_id"=>"required",
        ]);
        if ($validator->fails()) {
            return $this->Response(null, $validator->errors(), 400);
        }
        [$user_id, $provider_id] = $this->getUserAndProviderId($request);
        
        $room =$this->getOrCreateChatRoom($user_id, $provider_id, $request->request_id);

        if (!$room) {
            return $this->Response(null, __("messages.Failed to find or create a chat room."), 500);
        }
        return $this->Response($room->messages, __("messages.Messages Fetched Successfully"), 200);

    }
    public function send_message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required",
            "receiver_id"=>"required",
            "message" => "required",
        ]);
        if ($validator->fails()) {
            return $this->Response(null, $validator->errors(), 400);
        }
        [$user_id, $provider_id] = $this->getUserAndProviderId($request);
        
        $room =$this->getOrCreateChatRoom($user_id, $provider_id, $request->request_id);

        if (!$room) {
            return $this->Response(null, __("messages.Failed to find or create a chat room."), 500);
        }
        $message=ChatMessages::create([
            "sender_id"=>$request->user()->id,
            "sender_type"=>get_class($request->user()),
            "message"=>$request->message,
            "room_id"=>$room->id, 
        ]);
        broadcast(new MessageEvent($message));

        $type=get_class($request->user());
        if($type=="App\Models\User")
            $account=$room->provider;
        else 
            $account=$room->user;
        $data = [
            "user" => $account,
            "title" => "New Message",
            "description" => "You have a new message from " . $request->user()->name,
            "model_type" => "new_message",
            "model_id" =>$room->id,
            "provider_id" => $provider_id,
            "user_id" => $user_id,
            "request_id" => $request->request_id,
        ];
        Helpers::push_notification($data);
        return $this->Response($message, __("messages.Message Sent Successfully"), 201);
    }
}
