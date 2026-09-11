<?php

namespace App\Http\Requests\Api\V1\Students;

use App\Http\Requests\Api\V1\ApiFormRequest;

class UpdateStudentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['sometimes', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'academic_summary' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string'],
        ];
    }
}
