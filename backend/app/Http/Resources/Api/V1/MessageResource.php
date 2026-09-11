<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_type' => $this->sender_type,
            'sender_id' => $this->sender_id,
            'message_type' => $this->message_type,
            'body' => $this->body,
            'status' => $this->status,
            'sent_at' => $this->sent_at,
        ];
    }
}
