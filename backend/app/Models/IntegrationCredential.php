<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegrationCredential extends Model
{
    protected $fillable = [
        'integration_id',
        'credential_type',
        'encrypted_value',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }
}
