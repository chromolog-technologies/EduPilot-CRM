<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditLogger
{
    public function log(
        string $action,
        ?Model $entity = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?User $user = null,
        ?Request $request = null
    ): void {
        $user ??= auth()->user();
        $request ??= request();

        AuditLog::withoutGlobalScopes()->create([
            'organization_id' => $user?->organization_id,
            'user_id' => $user?->id,
            'action' => $action,
            'entity_type' => $entity ? $entity::class : null,
            'entity_id' => $entity?->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
