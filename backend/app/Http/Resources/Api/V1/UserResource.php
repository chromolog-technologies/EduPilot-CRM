<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'branch_id' => $this->branch_id,
            'role_id' => $this->role_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'last_login_at' => $this->last_login_at,
            'role' => $this->whenLoaded('role'),
            'organization' => $this->whenLoaded('organization'),
            'branch' => $this->whenLoaded('branch'),
            'permissions' => $this->when(
                $this->relationLoaded('role') && $this->role,
                fn () => $this->role->permissions->pluck('slug')
            ),
        ];
    }
}
