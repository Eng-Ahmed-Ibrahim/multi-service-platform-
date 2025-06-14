<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Complaints;
use Illuminate\Http\Request;

class complaintsController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
        $complaint = Complaints::create([
            'subject' => $request->subject,
            'message' => $request->message,
            'sender_id' => $user->id,
            'sender_type' => get_class($user), 
        ]);

        return response()->json([
            'message' => 'Complaint submitted successfully',
            'complaint' => $complaint
        ], 201);
    }
}
