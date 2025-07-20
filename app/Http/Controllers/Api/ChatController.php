<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return ChatResource::collection(
            Chat::where('sender_id', auth()->id())
                ->orWhere('receiver_id', auth()->id())
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'type' => 'in:text,file,bot'
        ]);

        $data['sender_id'] = auth()->id();
        $chat = Chat::create($data);

        return new ChatResource($chat->load(['sender', 'receiver']));
    }
}
