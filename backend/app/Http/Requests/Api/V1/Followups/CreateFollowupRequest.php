<?php

namespace App\Http\Requests\Api\V1\Followups;

use App\Enums\FollowupType;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateFollowupRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'integer'],
            'lead_id' => ['nullable', 'integer'],
            'assigned_to' => ['nullable', 'integer'],
            'followup_type' => ['nullable', Rule::enum(FollowupType::class)],
            'scheduled_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
