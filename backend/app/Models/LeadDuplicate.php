<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadDuplicate extends Model
{
    protected $fillable = [
        'organization_id',
        'lead_id',
        'duplicate_lead_id',
        'match_type',
        'match_score',
        'matched_fields',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'matched_fields' => 'array',
        'reviewed_at' => 'datetime',
        'match_score' => 'decimal:2',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function duplicateLead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'duplicate_lead_id');
    }
}
