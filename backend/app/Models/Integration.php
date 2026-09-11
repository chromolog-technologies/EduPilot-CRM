<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Integration extends Model
{
    protected $fillable = [
        'organization_id',
        'type',
        'name',
        'provider',
        'status',
        'configuration',
        'last_synced_at',
        'created_by',
    ];

    protected $casts = [
        'configuration' => 'array',
        'last_synced_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(IntegrationCredential::class);
    }
}
