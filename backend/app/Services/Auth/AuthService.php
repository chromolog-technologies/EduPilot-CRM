<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Audit\AuditLogger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private AuditLogger $auditLogger) {}

    public function login(string $email, string $password, ?string $organizationCode = null): array
    {
        $query = User::withoutGlobalScopes()->with(['organization', 'role.permissions', 'branch']);

        if ($organizationCode) {
            $query->whereHas('organization', fn ($q) => $q->where('code', $organizationCode));
        }

        $user = $query->where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status->value !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['This account is inactive.'],
            ]);
        }

        $user->forceFill(['last_login_at' => now()])->save();
        $token = $user->createToken('api')->plainTextToken;

        $this->auditLogger->log('login', $user, null, null, $user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
        $this->auditLogger->log('logout', $user, null, null, $user);
    }

    public function refresh(User $user): string
    {
        $user->currentAccessToken()?->delete();

        return $user->createToken('api')->plainTextToken;
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->fill(collect($data)->only(['name', 'phone'])->all())->save();

        return $user->fresh(['organization', 'role', 'branch']);
    }

    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->forceFill(['password' => $newPassword])->save();
    }
}
