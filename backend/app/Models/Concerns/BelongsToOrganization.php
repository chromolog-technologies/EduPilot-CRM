<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToOrganization
{
    protected static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            $user = request()->user() ?? auth()->user();

            if ($user?->organization_id) {
                $builder->where(
                    $builder->getModel()->getTable().'.organization_id',
                    $user->organization_id
                );
            }
        });

        static::creating(function ($model) {
            $user = request()->user() ?? auth()->user();

            if (! $model->organization_id && $user?->organization_id) {
                $model->organization_id = $user->organization_id;
            }
        });
    }

    public function scopeForOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->withoutGlobalScope('organization')
            ->where($query->getModel()->getTable().'.organization_id', $organizationId);
    }
}
