<?php

namespace App\Http\Requests\Api\V1\Leads;

use App\Enums\LeadPriority;
use App\Enums\LeadStatus;
use App\Enums\LeadTemperature;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['sometimes', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'lead_source_id' => ['nullable', 'integer'],
            'priority' => ['nullable', Rule::enum(LeadPriority::class)],
            'temperature' => ['nullable', Rule::enum(LeadTemperature::class)],
            'lead_status' => ['nullable', Rule::enum(LeadStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
