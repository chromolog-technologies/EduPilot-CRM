<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadCapture extends Model
{
    protected $fillable = [
        'organization_id',
        'integration_id',
        'lead_source_id',
        'campaign_id',
        'external_lead_id',
        'external_reference',
        'payload',
        'capture_status',
        'processed_at',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function leadSource(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class);
    }
}
