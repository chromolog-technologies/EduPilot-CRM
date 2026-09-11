<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'document_requirement_id' => $this->document_requirement_id,
            'file_name' => $this->file_name,
            'status' => $this->status,
            'uploaded_at' => $this->uploaded_at,
            'remarks' => $this->remarks,
            'download_url' => URL::temporarySignedRoute(
                'api.v1.documents.download',
                now()->addMinutes(15),
                ['document' => $this->id]
            ),
            'requirement' => $this->whenLoaded('requirement'),
        ];
    }
}
