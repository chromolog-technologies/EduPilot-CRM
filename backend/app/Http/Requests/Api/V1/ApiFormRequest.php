<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

abstract class ApiFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->offsetUnset('organization_id');
    }

    public function organizationId(): int
    {
        return (int) $this->user()->organization_id;
    }
}
