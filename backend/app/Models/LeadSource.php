<?php

namespace App\Models;

use App\Enums\RecordStatus;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadSource extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'name',
        'code',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => RecordStatus::class,
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
