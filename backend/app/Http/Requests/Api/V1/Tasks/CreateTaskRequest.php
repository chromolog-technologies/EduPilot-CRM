<?php

namespace App\Http\Requests\Api\V1\Tasks;

use App\Enums\TaskPriority;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'student_id' => ['nullable', 'integer'],
            'assigned_to' => ['nullable', 'integer'],
            'priority' => ['nullable', Rule::enum(TaskPriority::class)],
            'due_at' => ['nullable', 'date'],
        ];
    }
}
