<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function paginate(User $actor, array $filters): LengthAwarePaginator
    {
        return User::query()
            ->where('organization_id', $actor->organization_id)
            ->with(['role', 'branch'])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 20));
    }

    public function counselors(User $actor)
    {
        return User::query()
            ->where('organization_id', $actor->organization_id)
            ->where(function ($q) {
                $q->whereHas('role', fn ($role) => $role->where('slug', 'counselor'))
                    ->orWhereHas('roles', fn ($role) => $role->where('slug', 'counselor'));
            })
            ->with('role')
            ->orderBy('name')
            ->get();
    }

    public function create(User $actor, array $data): User
    {
        return User::create([
            'organization_id' => $actor->organization_id,
            'branch_id' => $data['branch_id'] ?? $actor->branch_id,
            'role_id' => $data['role_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'status' => $data['status'] ?? 'active',
        ]);
    }

    public function update(User $user, array $data): User
    {
        $user->fill(collect($data)->except('password')->all());

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return $user->fresh(['role', 'branch']);
    }
}
