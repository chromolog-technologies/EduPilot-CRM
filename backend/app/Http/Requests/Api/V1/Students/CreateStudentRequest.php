<?php

namespace App\Http\Requests\Api\V1\Students;

use App\Http\Requests\Api\V1\ApiFormRequest;

class CreateStudentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'alternate_phone' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'academic_summary' => ['nullable', 'string'],
            'branch_id' => ['nullable', 'integer'],
            'parents' => ['nullable', 'array'],
            'parents.*.name' => ['required_with:parents', 'string'],
            'parents.*.phone' => ['nullable', 'string'],
            'parents.*.relationship' => ['nullable', 'string'],
            'parents.*.email' => ['nullable', 'email'],
            'parents.*.is_primary' => ['nullable', 'boolean'],
        ];
    }
}
