<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationFeature extends Model
{
    protected $fillable = [
        'organization_id',
        'feature_key',
        'enabled',
        'configuration',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'configuration' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
