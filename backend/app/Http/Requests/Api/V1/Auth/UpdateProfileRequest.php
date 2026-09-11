<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\V1\ApiFormRequest;

class UpdateProfileRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
