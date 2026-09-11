<?php

namespace App\Http\Requests\Api\V1\Leads;

use App\Http\Requests\Api\V1\ApiFormRequest;

class AssignLeadRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'assigned_counselor_id' => ['required', 'integer'],
        ];
    }
}
