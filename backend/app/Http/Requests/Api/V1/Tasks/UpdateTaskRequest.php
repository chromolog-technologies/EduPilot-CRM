<?php

namespace App\Http\Requests\Api\V1\Tasks;

use App\Http\Requests\Api\V1\ApiFormRequest;

class UpdateTaskRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'integer'],
            'due_at' => ['nullable', 'date'],
        ];
    }
}
