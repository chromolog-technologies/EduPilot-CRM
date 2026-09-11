<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'assigned_to' => $this->assigned_to,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'due_at' => $this->due_at,
            'status' => $this->status,
            'completed_at' => $this->completed_at,
            'student' => $this->whenLoaded('student'),
            'assignee' => new UserResource($this->whenLoaded('assignee')),
            'created_at' => $this->created_at,
        ];
    }
}
