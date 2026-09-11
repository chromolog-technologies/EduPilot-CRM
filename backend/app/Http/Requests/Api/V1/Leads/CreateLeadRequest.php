<?php

namespace App\Http\Requests\Api\V1\Leads;

use App\Enums\LeadPriority;
use App\Enums\LeadTemperature;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateLeadRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required_without:student_id', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required_without:student_id', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'student_id' => ['nullable', 'integer'],
            'lead_source_id' => ['nullable', 'integer'],
            'assigned_counselor_id' => ['nullable', 'integer'],
            'branch_id' => ['nullable', 'integer'],
            'priority' => ['nullable', Rule::enum(LeadPriority::class)],
            'temperature' => ['nullable', Rule::enum(LeadTemperature::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
