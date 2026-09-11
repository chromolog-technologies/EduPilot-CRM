<?php

namespace App\Http\Requests\Api\V1\Leads;

use App\Enums\LeadStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadStatusRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'lead_status' => ['required', Rule::enum(LeadStatus::class)],
        ];
    }
}
