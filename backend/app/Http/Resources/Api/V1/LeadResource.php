<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'lead_source_id' => $this->lead_source_id,
            'assigned_counselor_id' => $this->assigned_counselor_id,
            'lead_status' => $this->lead_status,
            'priority' => $this->priority,
            'temperature' => $this->temperature,
            'notes' => $this->notes,
            'student' => $this->whenLoaded('student'),
            'source' => $this->whenLoaded('source'),
            'counselor' => new UserResource($this->whenLoaded('counselor')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
