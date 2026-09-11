<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'channel' => $this->channel,
            'status' => $this->status,
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'created_at' => $this->created_at,
        ];
    }
}
