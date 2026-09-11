<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookEvent extends Model
{
    protected $fillable = [
        'organization_id',
        'integration_id',
        'event_id',
        'event_type',
        'payload',
        'signature',
        'processing_status',
        'processed_at',
        'error_message',
        'received_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
