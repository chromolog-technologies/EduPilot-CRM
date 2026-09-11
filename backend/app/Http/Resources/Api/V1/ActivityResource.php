<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'lead_id' => $this->lead_id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'subject' => $this->subject,
            'description' => $this->description,
            'metadata' => $this->metadata,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}
