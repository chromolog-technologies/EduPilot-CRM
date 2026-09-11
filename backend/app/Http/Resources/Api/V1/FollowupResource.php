<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'lead_id' => $this->lead_id,
            'assigned_to' => $this->assigned_to,
            'followup_type' => $this->followup_type,
            'scheduled_at' => $this->scheduled_at,
            'status' => $this->status,
            'notes' => $this->notes,
            'completed_at' => $this->completed_at,
            'student' => $this->whenLoaded('student'),
            'assignee' => new UserResource($this->whenLoaded('assignee')),
            'created_at' => $this->created_at,
        ];
    }
}
