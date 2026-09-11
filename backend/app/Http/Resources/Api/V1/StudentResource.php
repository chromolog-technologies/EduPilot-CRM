<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_code' => $this->student_code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'alternate_phone' => $this->alternate_phone,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'academic_summary' => $this->academic_summary,
            'status' => $this->status,
            'pipeline_stage_id' => $this->pipeline_stage_id,
            'pipeline_stage' => $this->whenLoaded('pipelineStage'),
            'parents' => $this->whenLoaded('parentContacts'),
            'lead' => $this->whenLoaded('lead', fn () => new LeadResource($this->lead)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
