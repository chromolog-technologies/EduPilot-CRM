<?php

namespace App\Http\Requests\Api\V1\Followups;

use App\Http\Requests\Api\V1\ApiFormRequest;

class UpdateFollowupRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'assigned_to' => ['nullable', 'integer'],
            'scheduled_at' => ['sometimes', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
