<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'message'    => $this->message,
            'type'       => $this->type,
            'is_read'    => $this->is_read,
            'created_at' => $this->created_at,
            'sender'     => new UserResource($this->whenLoaded('sender')),
            'receiver'   => new UserResource($this->whenLoaded('receiver')),
        ];
    }
}

